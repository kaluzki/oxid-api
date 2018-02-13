<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use function DI\object, DI\get, DI\value;
use OxidEsales\Facts\Facts;
use OxidEsales\UnifiedNameSpaceGenerator\UnifiedNameSpaceClassMapProvider;
use League\Flysystem;
use Psr\Container\ContainerInterface;

return [
    'path' => value(dirname(__DIR__)),
    Facts::class => object(),
    UnifiedNameSpaceClassMapProvider::class => object()->constructor(get(Facts::class)),

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
