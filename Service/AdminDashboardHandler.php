<?php

namespace Dywee\CMSBundle\Service;

use Symfony\Component\Routing\Router;

class AdminDashboardHandler{

    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getElement()
    {
        $menu = array(
            'key' => 'cms',
            'cards' => array(
                array(
                    'controller' => 'DyweeCMSBundle:Dashboard:Card'
                )
            )
        );

        return $menu;
    }
}