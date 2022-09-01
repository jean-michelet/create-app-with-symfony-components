<?php

use Doctrine\ORM\ORMSetup;

$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = ORMSetup::createAttributeMetadataConfiguration(
    [__DIR__."/src/Entity"],
    $isDevMode,
    $proxyDir,
    $cache
);

// database configuration parameters
$connexion = [
    'driver' => 'pdo_sqlite',
    'path' => __DIR__.'/db.sqlite',
];

