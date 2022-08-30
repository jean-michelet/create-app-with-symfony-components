<?php

namespace App;

use App\Event\ControllerEvent;
use App\Event\RequestEvent;
use App\Event\ResponseEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class App
{
    private EventDispatcherInterface $eventDispatcher;
    private UrlMatcherInterface $matcher;
    private ControllerResolverInterface $controllerResolver;
    private ArgumentResolverInterface $argumentResolver;

    public function __construct(EventDispatcherInterface $eventDispatcher, UrlMatcherInterface $matcher, $controllerResolver, ArgumentResolverInterface $argumentResolver)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->matcher = $matcher;
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
    }

    public function handle(Request $request): Response
    {
        try {
            $attributes = $this->matcher->match($request->getPathInfo());
            $request->attributes->add($attributes);

            $this->eventDispatcher->dispatch(new RequestEvent($request));

            $controller = $this->controllerResolver->getController($request);

            $this->eventDispatcher->dispatch(new ControllerEvent($controller));

            $arguments = $this->argumentResolver->getArguments($request, $controller);

            if (is_callable($attributes['_controller'])) {
                $response = $attributes['_controller'](...$arguments);
            } else {
                [$class, $method] = $attributes['_controller'];
                $response = (new $class)->$method(...$arguments);
            }

        } catch (ResourceNotFoundException $exception) {
            $response = new Response($exception, Response::HTTP_NOT_FOUND);
        } catch (\Exception $exception) {
            $response = new Response($exception, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->eventDispatcher->dispatch(new ResponseEvent($response));

        return $response;
    }
}
