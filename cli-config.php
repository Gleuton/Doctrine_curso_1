<?php
    /**
     * User: gleuton.pereira
     * Date: 30/04/2018
     */

    use Doctrine\ORM\Tools\Console\ConsoleRunner;

// replace with file to your own project bootstrap
    require_once __DIR__.'/src/doctrine.php';

// replace with mechanism to retrieve EntityManager in your app
    $entityManager = getEntityManager();

    return ConsoleRunner::createHelperSet($entityManager);
