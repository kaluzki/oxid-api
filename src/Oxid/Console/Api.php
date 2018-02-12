<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace kaluzki\Oxid\Console;

use function fn\map, fn\mapValue, fn\sub, fn\traverse;
use fn\Map\Sort;
use kaluzki\Oxid\Meta\EditionClass;
use OxidEsales\UnifiedNameSpaceGenerator\UnifiedNameSpaceClassMapProvider;
use Silly\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * console application for oxid api
 */
class Api extends Application
{
    /**
     * @var string
     */
    const VERSION = '0.0.1';

    /**
     * @inheritdoc
     */
    protected function getDefaultCommands()
    {
        return map(parent::getDefaultCommands(), function(Command $command) {
            return $command->setHidden(true);
        })->merge([
            $this->command('meta', function(SymfonyStyle $style, UnifiedNameSpaceClassMapProvider $provider) {
                $map = map($provider->getClassMap())->keys(function($className) {
                    return mapValue($class = new EditionClass($className))->andGroup($class->package);
                })->sort(Sort::KEYS);

                traverse($map, function($classes, $package) use($style) {
                    $style->section($package);
                    $style->listing(traverse($classes, function(EditionClass $class) {
                        return implode(' > ' , map([$class->class], $class->parents, function($class) {
                            return sub($class, strlen('OxidEsales\Eshop\\'));
                        })->map);
                    }));
                });
            })
        ]);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    public function __invoke(InputInterface $input, OutputInterface $output)
    {
        return $this->run(...func_get_args());
    }
}
