<?php
namespace Application;

class Container
{
    const MAX_NESTED_SERVICES = 30;

    protected $lvlNestedServices = 1;
    protected $instantiateServices = [];
    protected $servicesList = [];
    protected $config = [];

    public function __construct($config, $servicesList)
    {
        $this->servicesList = $servicesList;
        $this->config = $config;
    }

    public function addInstantiateService($serviceName, $instance)
    {
        $this->instantiateServices[$serviceName] = $instance;

        return $this;
    }

    public function hasInstantiateService($serviceName)
    {
        return isset($this->instantiateServices[$serviceName]);
    }

    public function getInstantiateService($serviceName)
    {
        if ($this->hasInstantiateService($serviceName)) {
            return $this->instantiateServices[$serviceName];
        } else {
            return $this->instantiateService($serviceName);
        }
    }

    public function instantiateService($serviceName)
    {
        if (!isset($this->servicesList[$serviceName])) {
            throw new \Exception(sprintf('This service : %s does not exist', $serviceName), 404);
        }

        $service = $this->servicesList[$serviceName];
        $parameters = [];
        if (isset($service['parameters']) && is_array($service['parameters']) && !empty($service['parameters'])) {
            foreach ($service['parameters'] as $param) {
                if (strpos($param, '#') === 0) {
                    $class = ltrim($param, '#');
                    $inst = $this->getInstantiateService($class);
                    $parameters[$class] = $inst;
                } elseif (strpos($param, '$') === 0) {
                    $var = ltrim($param, '$');
                    $parameters[$var] = $this->config[$var];
                }
            }
        }

        $reflection = new \ReflectionClass($service['class']);
        $classInstance = $reflection->newInstanceArgs($parameters);
        $this->addInstantiateService($serviceName, $classInstance);

        return $classInstance;
    }
}
