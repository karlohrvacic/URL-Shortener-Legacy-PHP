<?php

namespace App\Event;

use App\Entity\Url;
use Symfony\Contracts\EventDispatcher\Event;

class UrlVisitedEvent extends Event
{
    /**
     * @var Url $_url
     */
    private Url $_url;

    const NAME = "link.visited";

    public function __construct(Url $url)
    {
        $this->_url = $url;
    }

    /**
     * @return Url
     */
    public function getUrl(): Url
    {
        return $this->_url;
    }
}