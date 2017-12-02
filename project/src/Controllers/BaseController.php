<?php
namespace Controllers;

class BaseController
{
    const ENV_DEBUG = 1;
    const ENV_PROD = 2;

    private $env;
    private $router;
    private $session;
    private $db;
    private $server;
    private $parameters = array();
    private $header = array();
    private $view404;
    private $view500;

    public function __construct($env, $router, $session, $db, $server, $view404 = null, $view500 = null)
    {
        $this->env = self::ENV_DEBUG === $env ? self::ENV_DEBUG : self::ENV_PROD;
        $this->router = $router;
        $this->session = $session;
        $this->db = $db;
        $this->server = $server;
        $this->view404 = !is_null($view404) ? $view404 : dirname(__DIR__).'/Views/view404.phtml';
        $this->view500 = !is_null($view500) ? $view500 : dirname(__DIR__).'/Views/view500.phtml';
    }

    public function redirect($routeName, $params = array())
    {
        $router = $this->getRouter();
        if ($router->hasRoute($routeName)) {
            $route = $router->generateRoute($routeName, $params);
        } elseif (self::ENV_DEBUG === $this->getEnv()) {
            throw new Exception(sprintf('[DEBUG] The current route : %s was not found !', $routeName), 404);
        } else {
            return $this->render500();
        }

        header('Location: '.$route);
        exit;
    }

    public function renderView($viewPath, $headers = [])
    {
        if (file_exists($viewPath)) {
            extract($this->getParameters());
        } elseif (self::ENV_DEBUG === $this->getEnv()) {
            throw new Exception(sprintf('[DEBUG] The current view : %s was not found !', $viewPath), 404);
        } else {
            return $this->render404();
        }

        if (is_array($headers) && !empty($headers)) {
            foreach ($headers as $header => $item) {
                header($item['string'], $item['replace'], $item['code']);
            }
        }

        ob_start();
        include($viewPath);
        $view = ob_get_contents();
        ob_end_clean();
        return $view;
    }

    public function addParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    public function removeParameter($name)
    {
        unset($this->parameters[$name]);
    }

    public function hasParameter($name)
    {
        return isset($this->parameters[$name]);
    }

    public function resetParameters()
    {
        $this->parameters = [];
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getParameter($name)
    {
        return $this->parameters[$name];
    }

    public function render404()
    {
        $headers = [];
        $headers['http']['string'] = 'HTTP/1.1 404 Not Found';
        $headers['http']['replace'] = true;
        $headers['http']['code'] = 404;
        return $this->renderView($this->view404, $headers);
    }

    public function render500()
    {
        $headers = [];
        $headers['http']['string'] = 'HTTP/1.1 500 Internal Server Error';
        $headers['http']['replace'] = true;
        $headers['http']['code'] = 500;
        return $this->renderView($this->view500, $headers);
    }

    public function getEnv()
    {
        return $this->env;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function getDb()
    {
        return $this->db;
    }

    protected function checkCSRF($token_session, $token_form)
    {
        $origin = filter_input(INPUT_SERVER, 'HTTP_ORIGIN', FILTER_SANITIZE_URL);
        $referer = filter_input(INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_URL);

        if ($origin) {
            $requestPath = $origin;
        } elseif ($referer) {
            $parseUrl = parse_url($referer);
            $requestPath = $parseUrl['scheme']."//".$parseUrl['host'].':'.$parseUrl['port'];
        } else {
            $requestPath = '';
        }

        return ($token_session === $token_form && $this->server === $requestPath);
    }
}
