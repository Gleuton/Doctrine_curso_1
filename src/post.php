<?php

use \Psr\Http\Message\ServerRequestInterface;
use \Zend\Diactoros\Response;
use \Curso\Entity\{
    Post, Category
};

$map->get('post.list', '/{search}?',
    function (ServerRequestInterface $request, Response $response) use ($view, $entityManeger) {

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

$map->get('post.details', '/post/{id}',
    function (ServerRequestInterface $request, Response $response) use ($view, $entityManeger) {
        $id = $request->getAttribute('id');

        $postRepository = $entityManeger->getRepository(Post::class);
        $categoryRepository = $entityManeger->getRepository(Category::class);

        $post = $postRepository->find($id);
        $categories = $categoryRepository->findAll();

        return $view->render($response, 'details.phtml', [
                'post' => $post,
                'categories' => $categories
            ]
        );
    });
