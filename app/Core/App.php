<?php

namespace TableFootball\League\Core;

use TableFootball\League\Exceptions\RouteNotFoundException;

class App
{
    protected const NAMESPACE_PREFIX = 'TableFootball\\League\\';
    protected $request;

    public function __construct()
    {
        $this->request = Request::getRequest();
    }

    public function execute() : Response
    {
        $path = $this->request->getPath();

        $path = explode('/', $path, 2);

        if($path[0] !== 'api') {
            throw new RouteNotFoundException('Invalid Route');
        }

        $method = $this->request->getMethod();

        if(isset($path[1])) {
            return $this->executeController($path[1], $method);
        }  elseif($method === 'GET') {
            return $this->executeDefaultController();
        } else {
            throw new RouteNotFoundException("Api route / not found");
        }
    }

    protected function executeDefaultController()
    {
        $defaultPath = simplexml_load_file(__DIR__ . '/../routes.xml')->default->path;

        $controller = static::NAMESPACE_PREFIX . 'Controllers\\' . $defaultPath->controller;
        $controller = new $controller;

        return call_user_func([$controller, (string)$defaultPath->action]);
    }

    protected function executeController($path, $method)
    {
        $action = explode('/', $path);

        $pathsElements = simplexml_load_file(__DIR__ . '/../routes.xml');

        /** @var $configuredPath \SimpleXMLElement */
        foreach($pathsElements->path as $configuredPath) {
            if($method === strtoupper((string)$configuredPath->attributes()->method)) {
                $configuredAction = explode('/', $configuredPath->uri);
                $optionalVariables = $this->comparePathArrays($action, $configuredAction);
                if(is_array($optionalVariables)) {
                    break;
                }
            };
        }
        if(!isset($optionalVariables) || !is_array($optionalVariables)) {
            throw new RouteNotFoundException("Api route \"{$path}\" not found");
        }

        $controller = static::NAMESPACE_PREFIX . 'Controllers\\' . $configuredPath->controller;
        $controller = new $controller;

        return call_user_func_array([$controller, (string)$configuredPath->action], $optionalVariables);
    }

    protected function comparePathArrays(array $pathToCompare, array $comparedPath)
    {
        $count = count($pathToCompare);
        if ($count !== count($comparedPath)) {
            return false;
        }

        $variables = [];
        for ($x = 0; $x < $count; $x++) {
            if (preg_match('/^{[a-zA-Z][a-zA-Z0-9]*}$/', $comparedPath[$x])) {
                $variables[] = $pathToCompare[$x];
                continue;
            }

            if ($pathToCompare[$x] !== $comparedPath[$x]) {
                return false;
            }
        }
        return $variables;
    }

}
