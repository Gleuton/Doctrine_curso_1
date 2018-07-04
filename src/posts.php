<?php

use \Psr\Http\Message\ServerRequestInterface;
use \Zend\Diactoros\Response;

$map->get('posts.list', '/posts/',
    function (ServerRequestInterface $request, $response) use ($view, $entityManeger) {
        $repository = $entityManeger->getRepository(\Curso\Entity\Post::class);

        $posts = $repository->findAll();

        return $view->render($response, 'posts/list.phtml', compact('posts'));
    });

$map->get('posts.form.edit', '/posts/edit/{id}',
    function (ServerRequestInterface $request, $response) use ($view, $entityManeger) {

        $id = $request->getAttribute('id');

        $repository = $entityManeger->getRepository(\Curso\Entity\Post::class);
        $posts = $repository->find($id);

        return $view->render($response, 'posts/edit.phtml', compact('posts'));
    });

$map->get('posts.create', '/posts/create',
    function ($request, $response) use ($view) {
        return $view->render($response, 'posts/create.phtml');
    });

$map->post('posts.store', '/posts/store',
    function (ServerRequestInterface $request, $response) use ($view, $entityManeger, $generator) {
        $data = $request->getParsedBody();

        $posts = new \Curso\Entity\Post();
        $posts->setTitle($data['title'])
            ->setContent($data['content']);

        $entityManeger->persist($posts);
        $entityManeger->flush();
        $uri = $generator->generate('posts.list');
        return new Response\RedirectResponse($uri);
    });

$map->post('posts.edit', '/posts/edit/{id}/submit',
    function (ServerRequestInterface $request, $response) use ($view, $entityManeger, $generator) {
        $data = $request->getParsedBody();
        $id = $request->getAttribute('id');

        $repository = $entityManeger->getRepository(\Curso\Entity\Post::class);
        $posts = $repository->find($id);

        $posts->setTitle($data['title'])
            ->setContent($data['content']);

        $entityManeger->flush();

        $uri = $generator->generate('posts.list');
        return new Response\RedirectResponse($uri);
    });

$map->get('posts.remove', '/posts/remove/{id}/submit',
    function (ServerRequestInterface $request, $response) use ($view, $entityManeger, $generator) {
        $id = $request->getAttribute('id');

        $repository = $entityManeger->getRepository(\Curso\Entity\Post::class);
        $posts = $repository->find($id);

        $entityManeger->remove($posts);
        $entityManeger->flush();

        $uri = $generator->generate('posts.list');
        return new Response\RedirectResponse($uri);
    });

$map->get('posts.categories', '/posts/categories/{id}',
    function (ServerRequestInterface $request, $response) use ($view, $entityManeger) {

        $id = $request->getAttribute('id');

        $repository = $entityManeger->getRepository(\Curso\Entity\Post::class);
        $categoryRepository = $entityManeger->getRepository(\Curso\Entity\Category::class);
        $categories = $categoryRepository->findAll();
        $posts = $repository->find($id);

        return $view->render($response, 'posts/categories.phtml', [
                'posts' => $posts,
                'categories' => $categories
            ]
        );
    });