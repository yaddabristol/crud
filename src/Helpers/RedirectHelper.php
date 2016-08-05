<?php

namespace Yaddabristol\Crud\Helpers;

/**
 * Helper for deciding where to go after successful form submissions
 *
 * @author  Jake Gully <jake@yadda.co.uk>
 * @author  Andrew Ellender <andrew@yadda.co.uk>
 * @license MIT
 */
class RedirectHelper
{
    protected $base_route;
    protected $controller_method;
    protected $routes;

    public function __construct($controller)
    {
        $this->controller        = $controller;
        $this->base_route        = $controller->getRoute();
        $this->controller_method = $this->getMethod();
        $this->routes            = $controller->getRedirectRoutes();
    }

    /**
     * @todo   Put route name separator somewhere sensible
     * @param  string    $action
     * @return Redirect
     */
    public function getRedirect($action)
    {
        return redirect()->route($this->routes[$action]);
    }

    /**
     * Get the name of the current route method.
     * @param   Request  The current request
     * @return  string   E.g. index, create, update, etc.
     */
    protected function getMethod()
    {
        $route = $this->controller->getRequest()->route();

        if (is_null($route)) {
            $method = 'non-crud-route';
        } else {
            list($class, $method) = explode('@', $route->getActionName());
        }

        return $method;
    }
}
