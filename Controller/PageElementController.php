<?php

namespace Dywee\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageElementController extends Controller
{
    /**
     * @Route(name="cms_getPageElementDashboard_byAjax", path="admin/cms/pageElement/ajaxDashboard", options={"expose": true})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function ajaxDashboardAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $objectName = $request->get('objectName');
            $em = $this->getDoctrine()->getManager();

            switch ($objectName) {
                case 'form':
                    $repository = $em->getRepository('DyweeCMSBundle:CustomForm');
                    $formList = $repository->findAll();
                    $response = [];

                    foreach ($formList as $form) {
                        $response[] = ['id' => $form->getId(), 'name' => $form->getName()];
                    }

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
                default:
                    return new Response('object not found');
            }
        }
    }
}
