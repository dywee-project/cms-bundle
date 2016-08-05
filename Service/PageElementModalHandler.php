<?php

namespace Dywee\CMSBundle\Service;

class PageElementModalHandler{
    
    public function addPageElements()
    {
        return array(
            array('column' => 'col-md-2'),
            array(
                'key' => 'text',
                'icon' => 'pencil',
                'value' => 'Zone de texte',
                'modalLabel' => 'Choisissez une galerie à afficher sur la page',
                'routeName' => false,
                'routeForAdding' => false,
                'active' => true
            ),
            array(
                'key' => 'form',
                'icon' => 'check-square-o',
                'value' => 'Formulaire',
                'modalLabel' => 'Choisissez un formulaire à afficher sur la page',
                'routeName' => 'cms_customForm_json',
                'routeForAdding' => 'cms_customForm_add',
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
            array(
            'key' => 'carousel',
            'icon' => 'picture-o',
            'value' => 'Diaporama photos',
            'modalLabel' => 'Choisissez ds photos à ajouter à votre galerie photo',
            'routeName' => false,
            'routeForAdding' => 'dywee_customForm_add',
            'active' => true
            )
        );
    }
}