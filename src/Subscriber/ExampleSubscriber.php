<?php

namespace App\Subscriber;

use App\Event\ControllerEvent;
use App\Event\RequestEvent;
use App\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ExampleSubscriber implements EventSubscriberInterface
{
    public function onRequestEvent(RequestEvent $event): void
    {
//        dump('RequestEvent triggered', $event->getRequest());
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
//        dump('ControllerEvent triggered', $event->getController());
    }

    public function onResponseEvent(ResponseEvent $event): void
    {
//        dump('ResponseEvent triggered', $event->getResponse());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onRequestEvent',
            ControllerEvent::class => 'onControllerEvent',
            ResponseEvent::class => 'onResponseEvent',
        ];
    }
}
