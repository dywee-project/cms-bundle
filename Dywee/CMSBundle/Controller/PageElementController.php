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

            $websiteId = $this->container->getParameter('website.id');
            $websiteRepository = $em->getRepository('DyweeWebsiteBundle:Website');
            $website = $websiteRepository->findOneById($websiteId);

            switch($objectName)
            {
                case 'form': {
                    $repository = $em->getRepository('DyweeModuleBundle:DyweeForm');
                    $formList = $repository->findForJson($website);
                    return new Response(json_encode($formList));
                }
                case 'musicGallery': {
                    $repository = $em->getRepository('DyweeModuleBundle:MusicGallery');
                    $formList = $repository->findForJson($website);
                    return new Response(json_encode($formList));
                }
                default: return new Response('object not found');
            }
        }
    }
}
