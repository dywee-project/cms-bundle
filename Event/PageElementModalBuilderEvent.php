<?php

namespace Dywee\CMSBundle\Event;

use Dywee\CMSBundle\Entity\Page;
use Symfony\Component\EventDispatcher\Event;

class PageElementModalBuilderEvent extends Event
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

    public function getJSData()
    {
        $return = '';

        foreach ($this->getData()['plugins'] as $plugin) {
            if (strlen($return > 1)) {
                $return .= ',
                ';
            }

            $return .= '
            {
            ';

            $pluginContent = '';

            foreach ($plugin as $key => $value) {
                if (strlen($pluginContent) > 0) {
                    $pluginContent .= ',
                    ';
                }

                $pluginContent .= "$key: '$value'";
            }

            $return .= $pluginContent;

            $return .= '
            },
            ';
        }

        $this->data['plugins_js'] = $return;

        return $this->data;
    }

    public function addData($array, $type = Page::TYPE_NORMALPAGE)
    {
        $this->data['plugins'] = array_merge($this->data['plugins'], $array);
        return $this;
    }
}
