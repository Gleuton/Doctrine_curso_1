<?php

use \Psr\Http\Message\ServerRequestInterface;
use \Zend\Diactoros\Response;
use \Curso\Entity\{
    Post, Category
};

$map->get('home', '/{search}?',
    function (ServerRequestInterface $request, $response) use ($view, $entityManeger) {

        $postsRepository = $entityManeger->getRepository(Post::class);
        $categoryRepository = $entityManeger->getRepository(Category::class);
        $search = $request->getAttribute('search') ?? null;

        $posts = $postsRepository->findAll();

        if (!is_null($search)) {
            $queryBuilder = $postsRepository->createQueryBuilder('post');
            $queryBuilder->join('post.categories', 'categories')
                ->where(
                    $queryBuilder->expr()->eq('categories.id', $search)
                );
            $posts = $queryBuilder->getQuery()->getResult();
        }

        $categories = $categoryRepository->findAll();


        return $view->render($response, 'home.phtml', [
                'posts' => $posts,
                'categories' => $categories
            ]
        );
    });

$map->get('posts.list', '/posts/',
    function (ServerRequestInterface $request, $response) use ($view, $entityManeger) {
        $repository = $entityManeger->getRepository(Post::class);

        $posts = $repository->findAll();

        return $view->render($response, 'posts/list.phtml', compact('posts'));
    });

$map->get('posts.form.edit', '/posts/edit/{id}',
    function (ServerRequestInterface $request, $response) use ($view, $entityManeger) {

        $id = $request->getAttribute('id');

        $repository = $entityManeger->getRepository(Post::class);
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

        $repository = $entityManeger->getRepository(Post::class);
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

        $repository = $entityManeger->getRepository(Post::class);
        $posts = $repository->find($id);

        $entityManeger->remove($posts);
        $entityManeger->flush();

        $uri = $generator->generate('posts.list');
        return new Response\RedirectResponse($uri);
    });

$map->get('posts.categories', '/posts/categories/{id}',
    function (ServerRequestInterface $request, $response) use ($view, $entityManeger) {

        $id = $request->getAttribute('id');

        $repository = $entityManeger->getRepository(Post::class);
        $categoryRepository = $entityManeger->getRepository(Category::class);
        $categories = $categoryRepository->findAll();
        $posts = $repository->find($id);

        return $view->render($response, 'posts/categories.phtml', [
                'posts' => $posts,
                'categories' => $categories
            ]
        );
    });

$map->post('posts.set-categories', '/posts/{id}/set-categories',
    function (ServerRequestInterface $request, $response) use ($view, $entityManeger, $generator) {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();

        $postRepository = $entityManeger->getRepository(Post::class);
        $categoryRepository = $entityManeger->getRepository(Category::class);

        /** @var Post $post */
        $post = $postRepository->find($id);
        $post->getCategories()->clear();

        foreach ($data['categories'] as $idCategory) {
            /** @var Category $category */
            $category = $categoryRepository->find($idCategory);
            if (!empty($category)) {
                $post->addCategory($category);
            }
        }

        $entityManeger->flush();

        $uri = $generator->generate('posts.list');
        return new Response\RedirectResponse($uri);
    });