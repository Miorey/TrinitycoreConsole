<?php
/**
 * Created by PhpStorm.
 * User: clem
 * Date: 20/11/18
 * Time: 21:58
 */

namespace App\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\TrinityCore\TrinityClient;

class ServerShutdownCommand extends Command
{
    private $trinityClient;

    public function __construct(TrinityClient $trinityClient, ?string $name = null)
    {
        $this->trinityClient = $trinityClient;
        parent::__construct($name);
    }

    protected function configure()
    {

        $this
            // the name of the command (the part after "bin/console")
            ->setName('trinity:server:shutdown')

            // the short description shown while running "php bin/console list"
            ->setDescription('Shutdown the TrinityCore World server.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('No Help 4 U')
            ->addOption('delay','d', InputArgument::OPTIONAL, 'Time in seconds', 30)
            ->addOption('message','m', InputArgument::OPTIONAL, 'Time in seconds', 'The server will be stopped');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = "server shutdown {$input->getOption('delay')} {$input->getOption('message')}";
        $this->trinityClient->executeCommand($command);

    }


}