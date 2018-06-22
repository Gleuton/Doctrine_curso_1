<?php

use \Aura\Router\RouterContainer;
use \Zend\Diactoros\ServerRequestFactory;
use \Zend\Diactoros\Response;
use \Psr\Http\Message\ServerRequestInterface;

$request = ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$routerContainer = new RouterContainer();
$generator = $routerContainer->getGenerator();
$view = new \Slim\Views\PhpRenderer(__DIR__ . '/../templates/');
$map = $routerContainer->getMap();
$entityManeger = getEntityManager();


$map->get('categories.list', '/', function ($request, $response) use ($view, $entityManeger) {
    $repository = $entityManeger->getRepository(\Curso\Entity\Category::class);

    $categories = $repository->findAll();

    return $view->render($response, 'categories/list.phtml', compact('categories'));
});

$map->get('categories.create', '/create', function ($request, $response) use ($view) {
    return $view->render($response, 'categories/create.phtml');
});

$map->post('categories.store', '/store',
    function (ServerRequestInterface $request, $response) use ($view, $entityManeger, $generator) {
        $data = $request->getParsedBody();

        $category = new \Curso\Entity\Category();
        $category->setName($data['name']);

        $entityManeger->persist($category);
        $entityManeger->flush();
        $uri = $generator->generate('categories.list');
        return new Response\RedirectResponse($uri);
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
if ($response instanceof Response\RedirectResponse) {
    header("location:{$response->getHeader('location')[0]}");
} elseif ($response instanceof Response) {
    echo $response->getBody();
}