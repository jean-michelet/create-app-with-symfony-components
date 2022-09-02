<?php

use App\App;
use App\AppControllerResolver;
use App\Controller\BlogController;
use App\Subscriber\ExampleSubscriber;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$container = new ContainerBuilder();

[$config, $connexion] = require_once __DIR__."/../config/packages/doctrine.php";

$container->register('doctrine.orm.entity_manager', EntityManager::class)
    ->setFactory([EntityManager::class, 'create'])
    ->setArguments([$connexion, $config])
;

$container->register(BlogController::class, BlogController::class)
    ->setArguments([new Reference('doctrine.orm.entity_manager')]);

$container->register('router.request_context', RequestContext::class)
    ->addMethodCall('fromRequest', [$request]);

$container->register('router.url_matcher', UrlMatcher::class)
    ->setArguments([$routeCollection, new Reference('router.request_context')]);

$container->register('kernel.controller_resolver', AppControllerResolver::class)
    ->setArguments([null, $container]);

$container->register('kernel.argument_resolver', ArgumentResolver::class);

$container->register('event_dispatcher', EventDispatcher::class)
    ->addMethodCall('addSubscriber', [new ExampleSubscriber()]);

$container->register('app', App::class)
    ->setArguments([
        new Reference('event_dispatcher'),
        new Reference('router.url_matcher'),
        new Reference('kernel.controller_resolver'),
        new Reference('kernel.argument_resolver')
    ]);

return $container;
