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

$configDB = file_get_contents(__DIR__ . '/../env.json');

$dbParams = (array)json_decode($configDB);

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