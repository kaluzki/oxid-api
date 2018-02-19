<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace kaluzki\Oxid\Console\Command;

use function fn\map, fn\sub, fn\mapRow, fn\traverse;
use kaluzki\Console\HereDocValidation;
use kaluzki\Console\Style;
use kaluzki\Oxid\Meta\EditionClass;
use League\Flysystem\Filesystem;
use OxidEsales\UnifiedNameSpaceGenerator\UnifiedNameSpaceClassMapProvider;
use Twig_Environment;

/**
 */
class Meta
{
    /**
     * @var UnifiedNameSpaceClassMapProvider
     */
    private $provider;

    /**
     * @var \League\Flysystem\Filesystem
     */
    private $fs;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @param UnifiedNameSpaceClassMapProvider $provider
     * @param Filesystem $fs
     * @param Twig_Environment $twig
     */
    public function __construct(UnifiedNameSpaceClassMapProvider $provider, Filesystem $fs, Twig_Environment $twig)
    {
        $this->provider = $provider;
        $this->fs = $fs;
        $this->twig = $twig;
    }

    /**
     * @param string $template
     * @param Style $style
     *
     * @return \Twig_Template
     */
    private function getTemplate($template, Style $style)
    {
        if (!$this->fs->has($template)) {
            $template = $style->ask(
                'Twig template (type <comment>EOT</comment> to finish the input)',
                null,
                new HereDocValidation('EOT')
            );
        } else if ($this->fs->get($template)->isDir()) {
            $file = $style->choice(
                'Choose twig file',
                traverse($this->fs->listFiles($template), mapRow('path'))
            );
            $template  = $this->fs->get($file)->read();
        }  else {
            $template = $this->fs->get($template)->read();
        }
        return $this->twig->createTemplate($template);
    }

    /**
     * @param Style $style
     * @param string[] $patterns
     * @param string $namespace
     * @param string $template
     */
    public function __invoke(Style $style, array $patterns, $namespace, $template)
    {
        $filters = $this->filters($patterns);

        /** @var EditionClass[] $classes */
        $classes = map($this->provider->getClassMap(), function(array $info, $className) use($filters, $style) {
            $class = new EditionClass($className, $info);
            foreach ($filters as $filter) {
                if ($filter($class)) {
                    return $class;
                }
            }
            return null;
        });

        $dir = 'resources/generated/';
        if ($template = $template ? $this->getTemplate($template, $style) : null) {
            $success = $this->fs->deleteDir($dir);
            $style->isVerbose() && ($success ? $style->success("deleted $dir") : $style->note("not found $dir"));
        }

        foreach ($classes as $class) {
            $ns = "{$namespace}{$class->package}";
            $path = str_replace('\\', DIRECTORY_SEPARATOR, "$dir/$ns/{$class->shortName}.php");

            $style->isVerbose() ? $this->classInfo($class, $path, $style) :  $style->writeln($class->class);

            if ($template) {
                $success = $this->fs->write(
                    $path,
                    $template->render(['class' => $class, 'namespace' => $ns, 'path' => $path])
                );
                $style->isVerbose() && ($success ? $style->success($path) : $style->error($path));
            }
        }
    }

    /**
     * @param EditionClass $class
     * @param string $path
     * @param Style $style
     */
    private function classInfo(EditionClass $class, $path, Style $style)
    {
        $style->title("<info>{$class->class}</info>");
        $style->listing([
            'name: ' . $class->shortName,
            'parent: ' . $class->editionClassName,
            'abstract: ' . json_encode($class->isAbstract),
            'interface: ' . json_encode($class->isInterface),
            'namespace: ' . $class->namespace,
            'package: ' . $class->package,
            'path: ' .  $path,
        ]);
        $style->section('Parents');
        $style->listing(traverse($class->parents, function($parent) {
            return str_replace(EditionClass::NS, '', $parent);
        }));
        if ($table = $class->table) {
            $style->section("table: {$table}");
            $style->listing($class->fields);
        }
    }

    /**
     * example:
     *
     * **          > all classes
     * Core\Base   > given class
     * Core\Base*  > subclasses of the given class
     * Core\Base** > given class with its subclasses
     *
     * the namespace OxidEsales\Eshop\ will be prefixed
     *
     * @param array $patterns
     * @return \fn\Map|\Closure[]
     */
    private function filters(array $patterns)
    {
        return map($patterns, function($aPattern) {
            if ($aPattern === '**') {
                return function() {
                    return true;
                };
            }
            if (strpos($aPattern, EditionClass::NS) === false) {
                $aPattern = EditionClass::NS . $aPattern;
            }
            if (sub($aPattern, -2) === '**') {
                $aPattern = sub($aPattern, 0, -2);
                return function(EditionClass $class) use($aPattern) {
                    return is_a($class->class, $aPattern, true);
                };
            }
            if (sub($aPattern, -1) === '*') {
                $aPattern = sub($aPattern, 0, -1);
                return function(EditionClass $class) use($aPattern) {
                    return is_subclass_of($class->class, $aPattern, true);
                };
            }
            return function(EditionClass $class) use($aPattern) {
                return strcasecmp($class->class, $aPattern) === 0;
            };
        });
    }
}
