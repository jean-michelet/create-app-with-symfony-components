<?php

use App\Controller\BlogController;
use App\Controller\HomeController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routeCollection = new RouteCollection();
$routeCollection->add('home', new Route('/{name}', [
    '_controller' => [HomeController::class, 'index'],
    'name' => 'Pascal'
]));

$routeCollection->add( 'blog', new Route('/blog', [
        '_controller' => [BlogController::class, 'index'],
    ]
));

$routeCollection->add( 'blog_new', new Route('/blog/new', [
        '_controller' => [BlogController::class, 'new'],
    ]
));

$routeCollection->add( 'blog_show', new Route('/blog/show/{id}', [
    '_controller' => [BlogController::class, 'show'],
], [
        'id' => '\d+'
    ]
));

$routeCollection->add( 'api', new Route('/api', [
    '_controller' => function (): Response {
        $json = ['token' => 'secret145265'];

        return new JsonResponse($json);
    }
]));

return $routeCollection;
