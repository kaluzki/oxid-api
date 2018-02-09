<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace kaluzki\Oxid\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

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
    public function __construct($name)
    {
        parent::__construct($name, self::VERSION);
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultCommands()
    {
        return \fn\map(parent::getDefaultCommands(), function(Command $command) {
            return $command->setHidden(true);
        })->merge([
            (new Command('meta'))->setCode(function($ignore, OutputInterface $output) {
                $output->writeln(['', '<info>todo</info>', '']);
            }),
        ]);
    }
}
