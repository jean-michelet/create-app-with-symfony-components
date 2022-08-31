<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;

require_once __DIR__."/../vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
use Doctrine\ORM\ORMSetup;

$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = ORMSetup::createAttributeMetadataConfiguration(
    [__DIR__."/../src/Entity"],
    $isDevMode,
    $proxyDir,
    $cache
);

// database configuration parameters
$connexion = [
    'driver' => 'pdo_sqlite',
    'path' => __DIR__.'/../db.sqlite',
];

// obtaining the entity manager
try {
    $em = EntityManager::create($connexion, $config);
} catch (ORMException $e) {
    echo $e;
}
