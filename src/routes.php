<?php

use \Aura\Router\RouterContainer;
use \Zend\Diactoros\ServerRequestFactory;
use \Zend\Diactoros\Response;

$request = ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

    $routerContainer = new RouterContainer();
    $generator = $routerContainer->getGenerator();
    $view = new \Slim\Views\PhpRenderer(__DIR__ . '/../templates/');
    $map = $routerContainer->getMap();

    $map->get('home' ,'/', function($request, $response) use ($view){
        return $view->render($response,'categories/list.phtml');
    });

    $matcher = $routerContainer->getMatcher();

    $route = $matcher->match($request);

    foreach ($route->attributes as $key => $val) {
        $request = $request->withAttribute($key, $val);
    }

    $callable = $route->handler;

    /**
     * @var Response $response
     */
    $response = $callable($request, new Response());

    echo $response->getBody();