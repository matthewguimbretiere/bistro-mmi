<?php
// Lire les routes possibles
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $route) {
  require 'routes.php';
});
// récupérer la méthode et l'URL proposée par le client
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = trim(str_replace(APP_ROOT_URL, '', $_SERVER['REQUEST_URI']));
// r( $httpMethod, $uri );

// éliminer les paramètres (?foo=bar)
if (false !== $pos = strpos($uri, '?')) {
  $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

// effectuer l'analyse de la commande
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
// r( $routeInfo );
// traiter la commande (si c'est possible)
switch ($routeInfo[0]) {
case FastRoute\Dispatcher::NOT_FOUND:
  header('Status: 404 Not Found');
  $twig->display('404.html.twig');
  break;
case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
  $allowedMethods = $routeInfo[1];
  header('Status: 404 Not Found');
  $twig->display('404.html.twig');
  break;
case FastRoute\Dispatcher::FOUND:
  $handler = $routeInfo[1];
  $vars = $routeInfo[2];

  // analyser la route pour détecter une écriture en contrôleur@méthode
  $params = explode('@', $handler);

  if (count($params) > 1) {
    // traiter la méthode du contrôleur
    // les contrôleurs utilisent un espace de nom
    // post@index  doit appeler Controllers\PostController méthode index
    $controller = "App\Controllers\\" . $params[0];
    try {
      // la classe du contrôleur n'existe pas ...
      if (class_exists($controller) === false) {
        echo "<h1>Le contrôleur '$controller' n'existe pas.</h1>";
        die();
      }
      $controller = new $controller;
      $action = $params[1];
      // la méthode du contrôleur n'existe pas ...
      if (method_exists($controller, $action) === false) {
        echo "<h1>La méthode  '$action' du contrôleur n'existe pas. </h1>";
        die();
      }
      // appeler la méthode du contrôleur
      return call_user_func_array([$controller, $action], $vars);
    } catch (\Core\Router\RooterException $exception) {
      echo 'HTTP Error 404 Not Found';
    }
  } else {
    // appeler la fonction anonyme
    call_user_func_array($handler, $vars);
  }
  break;

  break;
}
