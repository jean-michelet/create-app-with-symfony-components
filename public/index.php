<?php

use App\App;
use App\AppControllerResolver;
use App\Controller\BlogController;
use App\Subscriber\ExampleSubscriber;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

require_once __DIR__.'/../vendor/autoload.php';

function render_view(string $path, array $vars = []): string
{
    extract($vars);
    ob_start();
    require_once __DIR__.'/../views/'.trim($path, '/');
    $content = ob_get_clean();

    if (isset($extends)) {
        ob_start();
        require_once __DIR__.'/../views/'.trim($extends, '/');

        return (string) ob_get_clean();
    }

    return $content;
}

$routeCollection = require_once __DIR__.'/../config/routes.php';

$request = Request::createFromGlobals();

$container = new ContainerBuilder();

require_once __DIR__."/../bootstrap-doctrine.php";

$container->register('doctrine.orm.entity_manager', EntityManager::class)
    ->setFactory([EntityManager::class, 'create'])
    ->setArguments([$connexion, $config])
;

$e1 = $container->get('doctrine.orm.entity_manager');

$container->register(BlogController::class, BlogController::class)
    ->setArguments([new Reference('doctrine.orm.entity_manager')]);

// $context = (new RequestContext())->fromRequest($request);
$container->register('router.request_context', RequestContext::class)
    ->addMethodCall('fromRequest', [$request]);

// $matcher = new UrlMatcher($routeCollection, $context);
$container->register('router.url_matcher', UrlMatcher::class)
    ->setArguments([$routeCollection, new Reference('router.request_context')]);

// // To generate urls/paths
//$generator = new UrlGenerator($routeCollection, $context);
//dd($generator->generate('home', [], UrlGeneratorInterface::ABSOLUTE_URL));

//$controllerResolver = new ControllerResolver();
$container->register('kernel.controller_resolver', AppControllerResolver::class)
    ->setArguments([null, $container]);

//$controllerResolver = new ArgumentResolver();
$container->register('kernel.argument_resolver', ArgumentResolver::class);

//$eventDispatcher = new EventDispatcher();
//$eventDispatcher->addSubscriber(new ExampleSubscriber());
$container->register('event_dispatcher', EventDispatcher::class)
    ->addMethodCall('addSubscriber', [new ExampleSubscriber()]);

//$app = new App($eventDispatcher, $matcher, $controllerResolver, $argumentResolver);
$container->register('app', App::class)
    ->setArguments([
        new Reference('event_dispatcher'),
        new Reference('router.url_matcher'),
        new Reference('kernel.controller_resolver'),
        new Reference('kernel.argument_resolver')
    ]);

$response = $container->get('app')->handle($request);

// To create primitive profiler
//if ($request->query->has('_debug')) {
//    $calledListeners = [];
//    $lastEvent = '';
//    foreach ($eventDispatcher->getCalledListeners() as $calledListeners) {
//        if ($calledListeners['event'] !== $lastEvent) {
//            $lastEvent = $calledListeners['event'];
//        }
//
//        $debug[$lastEvent][] = $calledListeners['pretty'];
//    };
//
//    dump($debug);
//}

$response->send();
exit;
