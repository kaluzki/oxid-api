<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace kaluzki\Oxid\Console\Command;

use function fn\map, fn\sub;
use kaluzki\Oxid\Meta\EditionClass;
use OxidEsales\UnifiedNameSpaceGenerator\UnifiedNameSpaceClassMapProvider;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 */
class Meta
{
    /**
     * @var UnifiedNameSpaceClassMapProvider
     */
    private $provider;

    /**
     * @param UnifiedNameSpaceClassMapProvider $provider
     */
    public function __construct(UnifiedNameSpaceClassMapProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param SymfonyStyle $style
     * @param string[] $patterns
     */
    public function __invoke(SymfonyStyle $style, array $patterns)
    {
        $filters = $this->filters($patterns);

        /** @var EditionClass[] $classes */
        $classes = map($this->provider->getClassMap())->keys(function($className) use($filters) {
            $class = new EditionClass($className);
            foreach ($filters as $filter) {
                if ($filter($class)) {
                    return $class;
                }
            }
            return null;
        });

        foreach ($classes as $class) {
            $style->writeln($class->class);
            $style->isVerbose() && $style->listing($class->parents);
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
