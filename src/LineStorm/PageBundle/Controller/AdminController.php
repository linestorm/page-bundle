<?php

namespace LineStorm\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminController extends Controller
{
    public function listAction()
    {
        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $modelManager = $this->get('linestorm.cms.model_manager');

        $pages = $modelManager->get('page')->findAll();

        return $this->render('LineStormPageBundle:Admin:list.html.twig', array(
            'pages' => $pages,
        ));
    }


    public function viewAction($id)
    {
        $moduleManager = $this->get('linestorm.cms.module_manager');
        $module        = $moduleManager->getModule('page');

        $modelManager = $this->get('linestorm.cms.model_manager');
        $page = $modelManager->get('page')->find($id);

        return $this->render('LineStormPageBundle:Admin:view.html.twig', array(
            'page'      => $page,
            'module'    => $module,
        ));
    }


    public function editAction($id)
    {
        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $moduleManager = $this->get('linestorm.cms.module_manager');
        $module        = $moduleManager->getModule('page');

        $modelManager = $this->get('linestorm.cms.model_manager');

        $page = $modelManager->get('page')->find($id);

        $form = $this->createForm('linestorm_cms_form_page', $page, array(
            'action' => $this->generateUrl('linestorm_cms_module_page_api_put_page', array('id' => $page->getId())),
            'method' => 'PUT',
        ));

        return $this->render('LineStormPageBundle:Admin:edit.html.twig', array(
            'page'      => $page,
            'form'      => $form->createView(),
            'module'    => $module,
        ));
    }


    public function newAction()
    {
        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $moduleManager = $this->get('linestorm.cms.module_manager');
        $module        = $moduleManager->getModule('page');

        $modelManager = $this->get('linestorm.cms.model_manager');
        $page = $modelManager->create('page');

        $form = $this->createForm('linestorm_cms_form_page', $page, array(
            'action' => $this->generateUrl('linestorm_cms_module_page_api_post_page'),
            'method' => 'POST',
        ));

        return $this->render('LineStormPageBundle:Admin:new.html.twig', array(
            'page'      => null,
            'form'      => $form->createView(),
            'module'    => $module,
        ));
    }

}
