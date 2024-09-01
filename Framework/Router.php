<?php

namespace Framework;

use App\Controllers\FormController;
use App\Controllers\HomeController;
use App\Controllers\NamesController;

/**
 * The `Router` class is responsible for handling and dispatching HTTP requests to the appropriate
 * controller actions based on the registered routes. It maps URIs to specific controllers and actions,
 * processes the request URI, and invokes the corresponding method in the controller.
 */
class Router {

  /**
   * @var array $routes
   * An associative array that stores all registered routes. Each route is associated with a controller,
   * an action method, and an HTTP method.
   */
  protected array $routes = [];

  /**
   * @var string $uri
   * Stores the processed URI from the incoming HTTP request. This is used to determine which route
   * should be matched and handled.
   */
  protected string $uri;

  /**
   * @var mixed $params
   * Stores any extracted parameters from the URI. These parameters can be passed to controller actions
   * when needed.
   */
  protected $params;

  /**
   * Automatically registers routes by mapping specific URIs to corresponding controllers and actions.
   * This method defines the available routes in the application, such as home, authentication, blogs,
   * dashboard, and category routes.
   */
  public function autoRegisterRoute(): void {
    // Home routes
    $this->routes['/'] = [
      'controller' => HomeController::class,
      'action' => 'index',
      'httpMethod' => 'get'
    ];
    $this->routes['/form-submit'] = [
      'controller' => FormController::class,
      'action' => 'getFormData',
      'httpMethod' => 'post'
    ];
    $this->routes['/fetch-all'] = [
      'controller' => NamesController::class,
      'action' => 'getAllNames',
      'httpMethod' => 'get'
    ];
  }

  /**
   * Dispatches the incoming HTTP request to the appropriate controller and action based on the URI.
   * The method first processes the URI and checks if it matches any registered route. If a match is found,
   * it checks the HTTP method and then calls the specified action in the controller. If no match is found,
   * or if the HTTP method does not match, an exception is thrown.
   *
   * @throws \Exception if the route is not found or if the HTTP method is not allowed.
   */
  public function dispatch(): void {
    $uri = parse_url(rtrim($_SERVER['REQUEST_URI'], '/'), PHP_URL_PATH);
    $httpMethod = strtolower($_SERVER['REQUEST_METHOD'] ?? 'get');

    // Extract parameters from the URI if any
    $this->extractParams($uri);

    foreach ($this->routes as $routeUri => $routeDetails) {

      if ($this->matchUri($routeUri, $this->uri)) {
        if ($httpMethod !== $routeDetails['httpMethod']) {
          throw new \Exception("HTTP method not allowed for this route.");
        }

        $controller = new $routeDetails['controller'];
        $action = $routeDetails['action'] ?? 'index';

        if (method_exists($controller, $action)) {
          call_user_func_array([$controller, $action], [$this->params]);
        } else {
          throw new \Exception("Action $action not found in controller.");
        }

        return;
      }
    }

    throw new \Exception("Route not found.");
  }

  /**
   * Extracts any parameters from the URI and updates the internal state of the URI.
   * This method processes the URI segments, isolating any parameters that may be needed
   * by the controller's action.
   *
   * @param string $uri The URI string from the HTTP request.
   */
  private function extractParams(string $uri): void {
    $uriSegments = explode('/', trim($uri, '/'));

    if (isset($uriSegments[2])) {
      $this->params = $uriSegments[2];
      unset($uriSegments[2]);
      $uriSegments = array_values($uriSegments);
    }

    $uri = implode('/', $uriSegments) ?: '/';
    $this->uri = "/$uri";
  }

  /**
   * Compares the request URI with a registered route URI to determine if they match.
   *
   * @param string $routeUri The registered route URI.
   * @param string $requestUri The URI from the HTTP request.
   * @return bool Returns true if the URIs match, otherwise false.
   */
  private function matchUri(string $routeUri, string $requestUri): bool {
    return rtrim($routeUri, '/') === rtrim($requestUri, '/');
  }
}
