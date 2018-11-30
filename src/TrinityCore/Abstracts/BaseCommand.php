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
     * Name of the command
     * @var null|string
     */
    private $command = null;

    /**
     * Available methods
     * @var array
     */
    private $methods = [];

    /**
     * Commands which do not need to be prefixed with BASE command
     * @var array
     */
    private $doNotPrefix = [];

    /**
     * Commands which methods should be concatenated
     * @var array
     */
    private $concatenate = [];

    /**
     * BaseCommand constructor.
     * @param \SoapClient $client
     * @throws \ReflectionException
     */
    public function __construct(\SoapClient $client)
    {
        $this->setClientInstance($client);
        $this->prepareCommand();
        $this->prepareMethods();
    }

    /**
     * Get Command Name
     * @return string
     */
    public function getCommandName() : string
    {
        return $this->command;
    }

    /**
     * @return array
     */
    public function getDoNotPrefix(): array
    {
        return $this->doNotPrefix;
    }

    /**
     * @param array $doNotPrefix
     */
    public function setDoNotPrefix(array $doNotPrefix): void
    {
        $this->doNotPrefix = $doNotPrefix;
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
     * @return null|string
     */
    public function getCommand(): ?string
    {
        return $this->command;
    }

    /**
     * @param null|string $command
     */
    public function setCommand(?string $command): void
    {
        $this->command = $command;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param array $methods
     */
    public function setMethods(array $methods): void
    {
        $this->methods = $methods;
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
     * Get Command Methods
     * @return array
     */
    public function getCommandMethods() : array
    {
        return $this->methods;
    }

    /**
     * Execute Help Command
     * @param string $methodName
     * @return array
     * @codeCoverageIgnore
     */
    public function help(string $methodName = '')
    {
        return $this->processOutput($this->getClientInstance()->executeCommand(
            new \SoapParam(trim(sprintf('help %s %s', $this->getCommand(), $methodName)), 'command')), true);
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
        $this->setCommand(strtolower((new \ReflectionClass(get_called_class()))->getShortName()));
    }

    /**
     * Prepare Available Methods For Specified Command
     * @throws \ReflectionException
     */
    private function prepareMethods()
    {
        $classMethods = array_diff(get_class_methods(get_called_class()), get_class_methods(__CLASS__));
        foreach ($classMethods as $method) {
            $command = $this->generateCommand($method);
            $this->methods[$method] = [
                'command'   =>  $command,
                'query'     =>  $this->generateQueryString(get_called_class(), $method)
            ];
        }
    }

    /**
     * Generate Command String
     * @param string $method
     * @return string
     */
    private function generateCommand(string $method) : string
    {
        preg_match_all('/((?:^|[A-Z])[a-z]+)/', $method, $matches);
        $elements = array_map('strtolower', $matches[0]);
        $command = (!in_array($method, $this->getDoNotPrefix())) ? implode(' ', array_merge([$this->getCommand()], $elements)) : implode(' ', $elements);
        if (in_array($method, $this->getConcatenate())) {
            $command = $this->getCommand() . $method;
        }
        return trim($this->parseCommand($command));
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
     */
    protected function executeCommand(string $methodName, array $parameters)
    {
        $structure = [
            'class'         =>  get_called_class(),
            'method'        =>  $methodName,
            'parameters'    =>  $parameters,
            'query'         =>  $this->getMethods()[$methodName]
        ];
        print($structure['query']['query']."\n");

        print_r($structure['query']['query']);

        $prepared = trim(implode(' ', [
            $structure['query']['command'],
            str_replace(explode(' ', $structure['query']['query']), $structure['parameters'], $structure['query']['query'])
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

    /**
     * Parse and Process Command
     * @param string $command
     * @return string
     */
    private function parseCommand(string $command) : string
    {
        $replacements = [
            'gm level'              =>  'gmlevel',
            'game account create'   =>  'gameaccountcreate',
            'list game accounts'    =>  'listgameaccounts',
            'diff time'             =>  'difftime',
            'log level'             =>  'loglevel',
            'save all'              =>  'saveall',
            'name announce'         =>  'nameannounce',
            'change faction'        =>  'changefaction',
            'change race'           =>  'changerace'
        ];
        foreach ($replacements as $key => $value) {
            if (strstr($command, $key)) {
                $command = str_replace($key, $value, $command);
                break;
            }
        }
        return $command;
    }
}
