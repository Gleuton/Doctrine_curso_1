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
/*
    lembrar de criar argivo separado com configuracao do banco
*/
$dbParams = [
    'driver' => 'pdo_mysql',
    'user' => 'root',
    'password' => 'Tijolo',
    'dbname' => 'son_doctrine'
];

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
try {
    $entityManager = EntityManager::create($dbParams, $config);
} catch (\Doctrine\ORM\ORMException $e) {
}

function getEntityManager()
{
    global $entityManager;
    return $entityManager;
}