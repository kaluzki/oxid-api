<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace kaluzki\Oxid\Console;

use function fn\map;
use Silly\Application;
use Symfony\Component\Console;

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
        return map(parent::getDefaultCommands(), function(Console\Command\Command $command) {
            return $command->setHidden(true);
        })->merge([
            $this->command('meta', Command\Meta::class)
        ]);
    }

    /**
     * @param Console\Input\InputInterface $input
     * @param Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function __invoke(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        return $this->run(...func_get_args());
    }
}
