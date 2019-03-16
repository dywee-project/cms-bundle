<?php

namespace Dywee\CMSBundle\Service;

class AdminDashboardHandler
{

    public function getElement()
    {
        $menu = [
            'key'   => 'cms',
            'cards' => [
                [
                    'controller' => 'DyweeCMSBundle:Dashboard:Card',
                ],
            ],
        ];

        return $menu;
    }
}