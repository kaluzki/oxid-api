<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DI;

use OxidEsales\Facts\Facts;
use OxidEsales\UnifiedNameSpaceGenerator\UnifiedNameSpaceClassMapProvider;
use OxidEsales\Eshop\Core;
use League\Flysystem;
use Psr\Container\ContainerInterface;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_LoaderInterface;

return [
    'path' => value(dirname(__DIR__)),
    Facts::class => object(),
    UnifiedNameSpaceClassMapProvider::class => object()->constructor(get(Facts::class)),
    Core\FileCache::class => object(),
    Core\ShopIdCalculator::class => object()->constructor(get(Core\FileCache::class)),
    Core\SubShopSpecificFileCache::class => object()->constructor(get(Core\ShopIdCalculator::class)),
    Core\UtilsObject::class => factory([Core\Registry::class, 'getUtilsObject']),

    // twig
    'twig.options' => [],
    Twig_LoaderInterface::class => object(Twig_Loader_Filesystem::class)->constructor(get('path')),
    Twig_Environment::class => object()->constructor(
        get(Twig_LoaderInterface::class),
        get('twig.options')
    ),

    // filesystem
    'createFs' => value(function(Flysystem\AdapterInterface $adapter) {
        return (new Flysystem\Filesystem($adapter))
            ->addPlugin(new Flysystem\Plugin\ListPaths)
            ->addPlugin(new Flysystem\Plugin\ListFiles());
    }),
    Flysystem\Adapter\Local::class => object()->constructor(get('path')),
    Flysystem\Filesystem::class => function(Flysystem\Adapter\Local $local, ContainerInterface $c) {
        $createFs = $c->get('createFs');
        return $createFs($local);
    },
    Flysystem\MountManager::class => function(Flysystem\Filesystem $fs) {
        return new Flysystem\MountManager(['fs' => $fs]);
    }
];
