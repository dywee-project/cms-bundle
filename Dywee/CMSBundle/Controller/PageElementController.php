<?php

namespace Dywee\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageElementController extends Controller
{
    public function ajaxDashboardAction()
    {
        $request = $this->container->get('request');

        if($request->isXmlHttpRequest()) {
            $objectName = $this->get('request')->get('objectName');
            $em = $this->getDoctrine()->getManager();

            $websiteId = $this->get('session')->get('activeWebsite');
            $websiteRepository = $em->getRepository('DyweeWebsiteBundle:Website');
            $website = $websiteRepository->findOneById($websiteId);

            switch($objectName)
            {
                case 'form':
                    $repository = $em->getRepository('DyweeModuleBundle:DyweeForm');
                    $formList = $repository->findForJson($website);
                    return new Response(json_encode($formList));

                case 'musicGallery':
                    $repository = $em->getRepository('DyweeModuleBundle:MusicGallery');
                    $list = $repository->findForJson($website);
                    return new Response(json_encode($list));

                case 'carousel' :
                    $repository = $em->getRepository('DyweeModuleBundle:Carousel');
                    $list = $repository->findForJson($website);
                    return new Response(json_encode($list));
                default: return new Response('object not found');
            }
        }
    }
}
