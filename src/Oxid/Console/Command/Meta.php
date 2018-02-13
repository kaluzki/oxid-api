<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace kaluzki\Oxid\Console\Command;

use function fn\map, fn\mapValue, fn\sub, fn\traverse;
use fn\Map\Sort;
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
     */
    public function __invoke(SymfonyStyle $style)
    {
        $map = map($this->provider->getClassMap())->keys(function($className) {
            return mapValue($class = new EditionClass($className))->andGroup($class->package);
        })->sort(Sort::KEYS);

        traverse($map, function($classes, $package)  use($style) {
            $style->section($package);
            $style->listing(traverse($classes, function(EditionClass $class) {
                return implode(' > ' , map([$class->class], $class->parents, function($class) {
                    return sub($class, strlen(EditionClass::NS));
                })->map);
            }));
        });
    }
}
