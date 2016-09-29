<?php

namespace Dywee\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PageElementController extends Controller
{
    public function ajaxDashboardAction(Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $objectName = $request->get('objectName');
            $em = $this->getDoctrine()->getManager();

            $serializer = $this->get('serializer');
            $normalizer = new ObjectNormalizer();

            switch($objectName)
            {
                case 'form':
                    $repository = $em->getRepository('DyweeCMSBundle:CustomForm');
                    $formList = $repository->findAll();
                    $response = array();

                    foreach($formList as $form)
                        $response[] = array('id' => $form->getId(), 'name' => $form->getName());

                    return new Response(
                        json_encode($response)
                    );

                /*case 'musicGallery':
                    $repository = $em->getRepository('DyweeModuleBundle:MusicGallery');
                    $list = $repository->findForJson($website);
                    return new Response(json_encode($list));

                case 'carousel' :
                    $repository = $em->getRepository('DyweeModuleBundle:Carousel');
                    $list = $repository->findForJson($website);
                    return new Response(json_encode($list));*/
                default: return new Response('object not found');
            }
        }
    }
}
