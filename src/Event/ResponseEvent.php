<?php

namespace App\Event;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class ResponseEvent extends Event
{
    private Response $request;

    public function __construct(Response $request)
    {
        $this->request = $request;
    }

    public function getResponse(): Response
    {
        return $this->request;
    }

    public function setResponse(Response $request): void
    {
        $this->request = $request;
    }
}
