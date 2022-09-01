<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;

require_once __DIR__."/../vendor/autoload.php";

require_once __DIR__."/../bootstrap-doctrine.php";

// obtaining the entity manager
try {
    $em = EntityManager::create($connexion, $config);
} catch (ORMException $e) {
    echo $e;
}
