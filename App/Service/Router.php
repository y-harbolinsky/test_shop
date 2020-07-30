<?php declare(strict_types = 1);

use App\Controller\BaseController;

namespace App\Service;

/**
 * Class Router
 *
 * @package App\Service
 */
class Router
{
    /**
     * @var string
     */
    protected $controller = 'product';

    /**
     * @var string
     */
    protected $action = 'products';

    /**
     * @var array
     */
    protected $params = [];

    /**
     * Parse request url
     * @param Request $request
     */
    public function parse(Request $request): void
    {
        $url = trim($request->getUrl());

        if ($url === '/')
        {
            $this->controller = 'product';
            $this->action = 'products';
            $this->params = [];
        }
        else
        {
            $explode_url = explode('/', $url);
            $explode_url = array_slice($explode_url, 1);

            $this->controller = $explode_url[0] ?? 'product';
            $this->action = $explode_url[1] ?? 'products';
            $this->params = array_slice($explode_url, 2);
        }

        $controller = $this->loadController();

        if ($request->getContent())
        {
            $this->params = json_decode($request->getContent(), true);
        }

        if (!empty($_GET))
        {
            $this->params = $_GET;
        }

        call_user_func_array([$controller, $this->action], $this->params);
    }

    /**
     * @return mixed
     */
    public function loadController(): BaseController
    {
        $controller = 'App\Controller\\' . ucfirst($this->controller) . "Controller";

        return new $controller();
    }
}
