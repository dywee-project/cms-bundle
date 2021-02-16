<?php

namespace Dywee\CMSBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class HomepageBuilderEvent extends Event
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function addData($array)
    {
        $this->data = array_merge($this->data, $array);
        return $this;
    }
}
