<?php

namespace Dywee\CMSBundle\Service;

use Symfony\Component\Routing\Router;

class CMSAdminSidebarHandler{

    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getSideBarMenuElement()
    {
        $menu = array(
            'key' => 'cms',
            'icon' => 'fa fa-files-o',
            'label' => 'cms.sidebar.label',
            'children' => array(
                array(
                    'icon' => 'fa fa-list-alt',
                    'label' => 'cms.sidebar.table',
                    'route' => $this->router->generate('page_table')
                ),
                array(
                    'icon' => 'fa fa-list-alt',
                    'label' => 'cms.sidebar.form',
                    'route' => $this->router->generate('cms_customForm_table')
                ),
                array(
                    'icon' => 'fa fa-plus',
                    'label' => 'cms.add_page',
                    'route' => $this->router->generate('page_add')
                ),
            )
        );

        return $menu;
    }
}