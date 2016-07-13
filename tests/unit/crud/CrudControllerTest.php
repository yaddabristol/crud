<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Route;

use Yaddabristol\Crud\Controllers\CrudController;

class CrudControllerTest extends PHPUnit_Framework_TestCase
{
  protected $controller;

  public function setUp()
  {
    // Simulates Route parameters.
    $request = Request::create('/admin/things', 'GET');
    $request->setRouteResolver(function () use ($request) {
        $route = new Route('GET', '/admin/things', []);
        $route->bind($request);

        return $route;
    });

    $this->controller = $this->getMockForAbstractClass(CrudController::class, [$request]);
  }

  /**
   * @test: Tests the thing
   */
  public function testExistence()
  {

  }
}