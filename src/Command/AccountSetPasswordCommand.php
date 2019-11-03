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

class AccountSetPasswordCommand extends Command
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
            ->setName('trinity:account:set:password')

            // the short description shown while running "php bin/console list"
            ->setDescription('Change password for the given account')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Example trinity:account:set:password --account hello --password world')
            ->addOption('account','a', InputArgument::OPTIONAL, 'Principal account')
            ->addOption('password','p', InputArgument::OPTIONAL, 'New account password');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = "account set password {$input->getOption('account')} {$input->getOption('password')} {$input->getOption('password')}";
        $this->trinityClient->executeCommand($command);

    }


}