<?php namespace App\TrinityCore\Abstracts;

/**
 * Class BaseCommand
 * @package FreedomCore\TrinityCore\Console\Abstracts
 */
abstract class BaseCommand
{

    /**
     * Client Instance Object
     * @var \SoapClient
     */
    private $clientInstance = null;

    /**
     * Commands which methods should be concatenated
     * @var array
     */
    private $concatenate = [];

    /**
     * BaseCommand constructor.
     * @param \SoapClient $client
     */
    public function __construct(\SoapClient $client)
    {
        $this->setClientInstance($client);
    }

    /**
     * @return \SoapClient
     */
    public function getClientInstance(): \SoapClient
    {
        return $this->clientInstance;
    }

    /**
     * @param \SoapClient $clientInstance
     */
    public function setClientInstance(\SoapClient $clientInstance): void
    {
        $this->clientInstance = $clientInstance;
    }


    /**
     * @return array
     */
    public function getConcatenate(): array
    {
        return $this->concatenate;
    }

    /**
     * @param array $concatenate
     */
    public function setConcatenate(array $concatenate): void
    {
        $this->concatenate = $concatenate;
    }


    /**
     * Execute Help Command
     * @param string $methodName
     * @return array
     * @codeCoverageIgnore
     * @throws \ReflectionException
     */
    public function help(string $methodName = '')
    {
        return $this->processOutput($this->getClientInstance()->executeCommand(
            new \SoapParam(trim(sprintf('help %s %s', $this->prepareCommand(), $methodName)), 'command')), true);
    }

    /**
     * Add quotes to string
     * @param string $string
     * @return string
     */
    public function inQuotes(string $string) : string
    {
        return '"' . $string . '"';
    }

    /**
     * Prepare Command Name Variable
     * @throws \ReflectionException
     */
    private function prepareCommand()
    {
        return strtolower((new \ReflectionClass(get_called_class()))->getShortName());
    }

    /**
     * Generate Command Query String
     * @param string $class
     * @param string $method
     * @return string: list of command parameters
     * @throws \ReflectionException
     */
    private function generateQueryString(string $class, string $method) : string
    {
        $reflection =  new \ReflectionMethod($class, $method);

        return implode(' ', array_map(
            function (\ReflectionParameter $item) {
                return '%' . $item->getName() . '%';
                },
            $reflection->getParameters()
        ));
    }

    /**
     * Execute Command
     * @param string $methodName
     * @param array $parameters
     * @return array|string
     * @codeCoverageIgnore
     * @throws \ReflectionException
     */
    protected function executeCommand(string $methodName, array $parameters)
    {
        $fullCommand = $this->generateQueryString(get_called_class(), $methodName);

        $prepared = trim(implode(' ', [
            $this->prepareCommand(),
            $methodName,
            str_replace(explode(' ', $fullCommand), $parameters, $fullCommand)
        ]));

        return $this->processOutput($this->getClientInstance()->executeCommand(new \SoapParam($prepared, 'command')));

    }

    /**
     * Process Response
     * @param string $commandOutput
     * @param bool $helpFunction
     * @return array
     * @codeCoverageIgnore
     */
    private function processOutput(string $commandOutput, bool $helpFunction = false)
    {
        return array_filter(explode(PHP_EOL, $commandOutput));
    }

}
