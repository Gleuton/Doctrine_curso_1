<?php
    /**
     * User: gleuton.pereira
     * Date: 30/04/2018
     */

    use Doctrine\ORM\Tools\Setup;
    use Doctrine\ORM\EntityManager;

    $paths = [
        __DIR__ . '/Entity'
    ];

    $isDevMode = true;

    $dbParams = [
        'driver' => 'pdo_mysql',
        'user' => 'root',
        'password' => '',
        'dbname' => 'son_doctrine'
    ];

    $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
    $entityManager = EntityManager::create($dbParams, $config);

    function getEntityManager()
    {
        global $entityManager;
        return $entityManager;
    }