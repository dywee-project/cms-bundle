<?php

namespace Dywee\CMSBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class FooterBuilderEvent extends Event
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
