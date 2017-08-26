<?php

namespace Dywee\CMSBundle\Service;

use Symfony\Component\Routing\RouterInterface;

class AdminSidebarHandler
{
    /** @var RouterInterface */
    private $router;

    /**
     * AdminSidebarHandler constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @return array
     */
    public function getSideBarMenuElement()
    {
        $menu = [
            'key'      => 'cms',
            'icon'     => 'fa fa-files-o',
            'label'    => 'cms.sidebar.label',
            'children' => [
                [
                    'icon'  => 'fa fa-list-alt',
                    'label' => 'cms.sidebar.table',
                    'route' => $this->router->generate('page_table')
                ],
                [
                    'icon'  => 'fa fa-wp-forms',
                    'label' => 'cms.sidebar.form',
                    'route' => $this->router->generate('custom_form_table')
                ],
                [
                    'icon'  => 'fa fa-plus',
                    'label' => 'cms.sidebar.add_page',
                    'route' => $this->router->generate('page_add')
                ],
                [
                    'icon'  => 'fa fa-warning',
                    'label' => 'cms.sidebar.alert',
                    'route' => $this->router->generate('cms_alert_table')
                ],
            ]
        ];

        return $menu;
    }
}