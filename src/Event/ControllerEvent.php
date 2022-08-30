<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ControllerEvent extends Event
{
    private \Closure|array $controller;

    public function __construct(\Closure|array $controller)
    {
        $this->controller = $controller;
    }

    public function getController(): array|\Closure
    {
        return $this->controller;
    }

    public function setController($controller): void
    {
        $this->controller = $controller;
    }

}
