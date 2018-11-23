<?php namespace App\TrinityCore;

use App\TrinityCore\Abstracts\BaseClient;
use App\TrinityCore\Commands\Account;
use App\TrinityCore\Commands\BNetAccount;
use App\TrinityCore\Commands\GM;
use App\TrinityCore\Commands\Guild;
use App\TrinityCore\Commands\LFG;
use App\TrinityCore\Commands\Character;
use App\TrinityCore\Commands\Reset;
use App\TrinityCore\Commands\Send;
use App\TrinityCore\Commands\Server;

/**
 * Class Client
 * @package FreedomCore\TrinityCore\Console
 */
class Client extends BaseClient
{


    /**
     * Get Account Command Instance
     * @return Account
     * @throws \ReflectionException
     */
    public function account() : Account
    {
        return (new Account($this->getClient()));
    }

    /**
     * Get Bnet Command Instance
     * @return BNetAccount
     * @throws \ReflectionException
     */
    public function bnet() : BNetAccount
    {
        return (new BNetAccount($this->getClient()));
    }

    /**
     * Get Character Command Instance
     * @return Character
     * @throws \ReflectionException
     */
    public function character() : Character
    {
        return (new Character($this->getClient()));
    }

    /**
     * Get GM Command Instance
     * @return GM
     * @throws \ReflectionException
     */
    public function gm() : GM
    {
        return (new GM($this->getClient()));
    }

    /**
     * Get Guild Command Instance
     * @return Guild
     * @throws \ReflectionException
     */
    public function guild() : Guild
    {
        return (new Guild($this->getClient()));
    }

    /**
     * Get LFG Command Instance
     * @return LFG
     * @throws \ReflectionException
     */
    public function lfg() : LFG
    {
        return (new LFG($this->getClient()));
    }

    /**
     * Get Reset Command Instance
     * @return Reset
     * @throws \ReflectionException
     */
    public function reset() : Reset
    {
        return (new Reset($this->getClient()));
    }

    /**
     * Get Send Command Instance
     * @return Send
     * @throws \ReflectionException
     */
    public function send() : Send
    {
        return (new Send($this->getClient()));
    }

    /**
     * Get Server Command Instance
     * @return Server
     * @throws \ReflectionException
     */
    public function server() : Server
    {
        return (new Server($this->getClient()));
    }
}
