<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use function DI\object, DI\get;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Interop\Container\ContainerInterface;
use kaluzki\Oxid;

return [
    ArgvInput::class => object(),
    ConsoleOutput::class => object(),
    InputInterface::class => get(ArgvInput::class),
    SymfonyStyle::class => object()->constructor(
        get(InputInterface::class),
        get(ConsoleOutput::class)
    ),
    OutputInterface::class => get(SymfonyStyle::class),
    Oxid\Console\Api::class => function(ContainerInterface $c) {
        $console = new Oxid\Console\Api('oxid-api', Oxid\Console\Api::VERSION);
        $console->useContainer($c, true);
        return $console;
    }
];
