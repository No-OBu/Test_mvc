<?php
namespace Application;

class Application
{
    private $container;
    private $request;
    private $router;

    public function __construct($config, $services, $route)
    {
        $config['route'] = $route;
        $this->container = new Container($config, $services);
    }

    public function init()
    {
        $this->request = filter_input(INPUT_SERVER, "REQUEST_URI", FILTER_SANITIZE_URL);
        $this->router = $this->container->getInstantiateService('router');
    }

    public function start()
    {
        $route = $this->router->findRoute($this->request);
        if (!is_null($route['controller'])) {
            $instController = $this->container->getInstantiateService($route['controller']);
            echo $instController->$route['action']();
        } else {
            $baseController = $this->container->getInstantiateService('BaseController');
            echo $baseController->render404();
        }
    }
}
