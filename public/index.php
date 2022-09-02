<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;

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

/** @var ContainerBuilder $container */
$container = include_once __DIR__.'/../config/services.php';

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
