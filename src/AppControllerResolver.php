<?php

namespace App;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

class AppControllerResolver extends ControllerResolver
{
    private ContainerInterface $container;

    public function __construct(LoggerInterface $logger = null, ContainerInterface $container)
    {
        parent::__construct($logger);
        $this->container = $container;
    }

    protected function instantiateController(string $class): object
    {
        if ($this->container->has($class)) {
            return $this->container->get($class);
        }

        return new $class();
    }
}
