<?php

use App\App;
use App\Subscriber\ExampleSubscriber;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

require_once __DIR__.'/../vendor/autoload.php';

function render_view(string $page, array $vars = []): string
{
    ob_start();
    extract($vars);
    require_once __DIR__.'/../views/'.$page.'.php';
    return (string) ob_get_clean();
}

$routeCollection = require_once __DIR__.'/../config/routes.php';

$request = Request::createFromGlobals();

$container = new ContainerBuilder();

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
$container->register('kernel.controller_resolver', ControllerResolver::class);

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
