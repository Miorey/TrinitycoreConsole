<?php
/**
 * Created by PhpStorm.
 * User: clem
 * Date: 20/11/18
 * Time: 21:58
 */

namespace App\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\TrinityCore\TrinityClient;

class SRP6Command extends Command
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
            ->setName('srp6:verifier')

            // the short description shown while running "php bin/console list"
            ->setDescription('Generetate a verifier for the given user password salt')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('To be defined');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getOption('username');
        $password = $input->getOption('password');
        $salt = $input->getOption('salt');

        // algorithm constants
        $g = gmp_init(7);
        $N = gmp_init('894B645E89E1535BBDAD5B8B290650530801B18EBFBF5E8FAB3C82872A3E9BB7', 16);

        // calculate first hash
        $h1 = sha1(strtoupper($username . ':' . $password), TRUE);

        // calculate second hash
        $h2 = sha1($salt.$h1, TRUE);

        // convert to integer (little-endian)
        $h2 = gmp_import($h2, 1, GMP_LSW_FIRST);

        // g^h2 mod N
        $verifier = gmp_powm($g, $h2, $N);

        // convert back to a byte array (little-endian)
        $verifier = gmp_export($verifier, 1, GMP_LSW_FIRST);

        // pad to 32 bytes, remember that zeros go on the end in little-endian!
        $verifier = str_pad($verifier, 32, chr(0), STR_PAD_RIGHT);

        // done!
        return $verifier;

    }


}