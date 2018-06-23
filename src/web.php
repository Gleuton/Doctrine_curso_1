<?php

use \Psr\Http\Message\ServerRequestInterface;
use \Zend\Diactoros\Response;

$map->get('categories.list', '/',
    function (ServerRequestInterface $request, $response) use ($view, $entityManeger) {
        $repository = $entityManeger->getRepository(\Curso\Entity\Category::class);

        $categories = $repository->findAll();

        return $view->render($response, 'categories/list.phtml', compact('categories'));
    });

$map->get('categories.form.edit', '/edit/{id}',
    function (ServerRequestInterface $request, $response) use ($view, $entityManeger) {

        $id = $request->getAttribute('id');

        $repository = $entityManeger->getRepository(\Curso\Entity\Category::class);
        $category = $repository->find($id);

        return $view->render($response, 'categories/edit.phtml', compact('category'));
    });

$map->get('categories.create', '/create',
    function ($request, $response) use ($view) {
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

$map->post('categories.edit', '/edit/{id}/submit',
    function (ServerRequestInterface $request, $response) use ($view, $entityManeger, $generator) {
        $data = $request->getParsedBody();
        $id = $request->getAttribute('id');

        $repository = $entityManeger->getRepository(\Curso\Entity\Category::class);
        $category = $repository->find($id);

        $category->setName($data['name']);

        $entityManeger->flush();

        $uri = $generator->generate('categories.list');
        return new Response\RedirectResponse($uri);
    });