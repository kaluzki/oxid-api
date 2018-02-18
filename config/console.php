<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DI;

use kaluzki\Console\Style;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Interop\Container\ContainerInterface;
use kaluzki\Oxid;

return [
    ArgvInput::class => object(),
    ConsoleOutput::class => object(),
    InputInterface::class => get(ArgvInput::class),
    Style::class => object()->constructor(
        get(InputInterface::class),
        get(ConsoleOutput::class)
    ),
    OutputInterface::class => get(Style::class),
    Oxid\Console\Api::class => function(ContainerInterface $c) {
        $console = new Oxid\Console\Api('oxid-api', Oxid\Console\Api::VERSION);
        $console->useContainer($c, true);
        return $console;
    }
];
