<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UrlVisitedSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return[
            UrlVisitedEvent::NAME => 'onUrlVisited'
        ];
    }

    public function onUrlVisited(UrlVisitedEvent $event)
    {

        dd($event->getUrl());
    }
}