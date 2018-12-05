<?php
namespace App\TrinityCore\Abstracts;

use App\TrinityCore\Exceptions\SoapException;

/**
 * Class BaseClient
 * @package FreedomCore\TrinityCore\Console\Abstracts
 */
class BaseClient
{

    /**
     * Server Address
     * @var string
     */
    private $serverAddress = '127.0.0.1';

    /**
     * Server Port
     * @var int
     */
    private $serverPort = 7878;

    /**
     * SoapClient Instance
     * @var \SoapClient
     */
    private $client;

    /**
     * Username used to connect to the server
     * @var null|string
     */
    private $username = null;

    /**
     * Password used to connect to the server
     * @var null|string
     */
    private $password = null;

    /**
     * BaseClient constructor.
     * @param string $username Username used to connect to the server
     * @param string $password Password used to connect to the server
     * @throws SoapException
     */
    public function __construct(string $username, string $password)
    {
        $this->isSoapEnabled();
        $this->setUsername($username);
        $this->setPassword($password);
        $this->createConnection();
    }

    /**
     * @return string
     */
    public function getServerAddress(): string
    {
        return $this->serverAddress;
    }

    /**
     * @param string $serverAddress
     */
    public function setServerAddress(string $serverAddress): void
    {
        if ($serverAddress==='') {
            throw new \RuntimeException('SOAP Address cannot be null!');
        }
        $this->serverAddress = $serverAddress;
    }




    /**
     * @return int
     */
    public function getServerPort(): int
    {
        return $this->serverPort;
    }

    /**
     * @param int $serverPort
     */
    public function setServerPort(int $serverPort): void
    {
        if ($serverPort < 1) {
            throw new \RuntimeException('SOAP Port cannot be null!');
        }
        $this->serverPort = $serverPort;
    }



    /**
     * Set username variable
     * @param string $username
     */
    public function setUsername(string $username)
    {
        if ($username === '') {
            throw new \RuntimeException('SOAP Username cannot be null!');
        }
        $this->username = $username;
    }

    /**
     * Get username variable
     * @return string
     */
    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * Set password variable
     * @param string $password
     */
    public function setPassword(string $password)
    {
        if ($password === '') {
            throw new \RuntimeException('SOAP Password cannot be null!');
        }
        $this->password = $password;
    }

    /**
     * Get password variable
     * @return string
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * Set Server Address
     * @param string $serverAddress
     * @return BaseClient
     */
    public function setAddress(string $serverAddress) : BaseClient
    {
        $this->serverAddress = $serverAddress;
        return $this;
    }

    /**
     * Get Server Address
     * @return string
     */
    public function getAddress() : string
    {
        return $this->serverAddress;
    }

    /**
     * Set Server Port
     * @param int $serverPort
     * @return BaseClient
     */
    public function setPort(int $serverPort) : BaseClient
    {
        $this->serverPort = $serverPort;
        return $this;
    }

    /**
     * Get Server Port
     * @return int
     */
    public function getPort() : int
    {
        return $this->serverPort;
    }

    /**
     * Initialize Connection To The Server
     */
    public function createConnection()
    {
        $this->setClient(new \SoapClient(null, [
            'location'      =>  'http://' . $this->getServerAddress() . ':' . $this->getServerPort() . '/',
            'uri'           =>  'urn:TC',
            'login'         =>  $this->getUsername(),
            'password'      =>  $this->getPassword(),
            'style'         =>  SOAP_RPC,
            'keep_alive'    =>  false
        ]));


    }

    /**
     * Get Client Instance
     * @return \SoapClient
     */
    public function getClient() : \SoapClient
    {
        return $this->client;
    }

    /**
     * @param \SoapClient $client
     */
    public function setClient(\SoapClient $client): void
    {
        $this->client = $client;
    }



    /**
     * Check if SOAP extension is enabled
     * @throws SoapException
     * @codeCoverageIgnore
     */
    protected function isSoapEnabled()
    {
        if (!extension_loaded('soap')) {
            throw new SoapException('FreedomNet requires SOAP extension to be enabled.' . PHP_EOL . 'Please enable SOAP extension');
        }
    }



    /**
     * Execute Command
     * @param string $command
     * @return array|string
     * @codeCoverageIgnore
     */
    public function executeCommand(string $command)
    {
        return $this->getClient()->executeCommand(new \SoapParam($command, 'command'));
    }
}
