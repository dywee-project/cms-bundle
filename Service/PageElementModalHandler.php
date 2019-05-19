<?php

namespace Dywee\CMSBundle\Service;

class PageElementModalHandler
{
    
    public function addPageElements()
    {
        return array(
            array('column' => 'col-md-2'),
            array(
                'key' => 'text',
                'icon' => 'pencil',
                'value' => 'cms.plugin.text.label',
                'modalLabel' => 'cms.plugin.text.description',
                'routeName' => false,
                'routeForAdding' => false,
                'active' => true
            ),
            array(
                'key' => 'form',
                'icon' => 'check-square-o',
                'value' => 'cms.plugin.form.label',
                'modalLabel' => 'cms.plugin.form.description',
                'routeName' => 'cms_customForm_json',
                'routeForAdding' => 'custom_form_add',
                'active' => true
            ),

        /*{
            'key' => 'musicGallery',
            'icon' => 'music',
            'value' => 'Galerie musicale',
            'modalLabel' => 'Choisissez une galerie musicale à afficher sur la page',
            'routeName' => 'dywee_musicGallery_json',
            'routeForAdding' => 'dywee_musicGallery_add',
            'active' => true
        },*/
            /*array(
            'key' => 'carousel',
            'icon' => 'picture-o',
            'value' => 'cms.plugin.carousel.label',
            'modalLabel' => 'cms.plugin.carousel.description',
            'routeName' => false,
            'routeForAdding' => 'dywee_customForm_add',
            'active' => true
            )
            */
        );
    }
}
