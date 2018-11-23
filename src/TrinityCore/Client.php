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
     */
    public function account() : Account
    {
        return (new Account($this->client));
    }

    /**
     * Get Bnet Command Instance
     * @return BNetAccount
     */
    public function bnet() : BNetAccount
    {
        return (new BNetAccount($this->client));
    }

    /**
     * Get Character Command Instance
     * @return Character
     */
    public function character() : Character
    {
        return (new Character($this->client));
    }

    /**
     * Get GM Command Instance
     * @return GM
     */
    public function gm() : GM
    {
        return (new GM($this->client));
    }

    /**
     * Get Guild Command Instance
     * @return Guild
     */
    public function guild() : Guild
    {
        return (new Guild($this->client));
    }

    /**
     * Get LFG Command Instance
     * @return LFG
     */
    public function lfg() : LFG
    {
        return (new LFG($this->client));
    }

    /**
     * Get Reset Command Instance
     * @return Reset
     */
    public function reset() : Reset
    {
        return (new Reset($this->client));
    }

    /**
     * Get Send Command Instance
     * @return Send
     */
    public function send() : Send
    {
        return (new Send($this->client));
    }
    
    /**
     * Get Server Command Instance
     * @return Server
     */
    public function server() : Server
    {
        return (new Server($this->client));
    }
}
