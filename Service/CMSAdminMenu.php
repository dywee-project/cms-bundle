<?php

namespace Dywee\CMSBundle\Service;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

class CMSAdminMenu{

    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getSideBarMenuElementAction()
    {
        $menu = array(
            'icon' => 'fa fa-files-o',
            'label' => 'Pages',
            'children' => array(
                array(
                    array(
                        'icon' => 'fa fa-list-alt',
                        'label' => 'Gestion des pages',
                        'route' => $this->router->generate('page_table')
                    ),
                    array(
                        'icon' => 'fa fa-plus',
                        'label' => 'Nouvelle page',
                        'route' => $this->router->generate('page_add')
                    ),
                )
            )
        );
    }
}