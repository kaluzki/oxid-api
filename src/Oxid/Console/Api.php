<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace kaluzki\Oxid\Console;

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
        return \fn\map(parent::getDefaultCommands(), function(Command $command) {
            return $command->setHidden(true);
        })->merge([
            $this->command('meta', function(
                SymfonyStyle $style,
                UnifiedNameSpaceClassMapProvider $provider
            ) {
                $style->writeln(\fn\map($provider->getClassMap())->keys);
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
