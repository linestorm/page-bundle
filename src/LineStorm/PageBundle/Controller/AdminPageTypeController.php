<?php

namespace LineStorm\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class PageTypeController
 *
 * @package LineStorm\PageBundle\Controller
 */
class AdminPageTypeController extends Controller
{
    /**
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $user = $this->getUser();
        if(!($user instanceof UserInterface) || !($user->hasGroup('admin')))
        {
            throw new AccessDeniedException();
        }

        $modelManager = $this->get('linestorm.cms.model_manager');

        $types = $modelManager->get('page_type')->findAll();

        return $this->render('LineStormPageBundle:Admin:PageType/list.html.twig', array(
            'types' => $types,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function newAction()
    {
        $user = $this->getUser();
        if(!($user instanceof UserInterface) || !($user->hasGroup('admin')))
        {
            throw new AccessDeniedException();
        }

        $moduleManager = $this->get('linestorm.cms.module_manager');
        $module        = $moduleManager->getModule('page');

        $modelManager  = $this->get('linestorm.cms.model_manager');
        $page          = $modelManager->create('page_type');

        $form = $this->createForm('linestorm_cms_form_page_type', $page, array(
            'action' => $this->generateUrl('linestorm_cms_module_page_type_api_post_type'),
            'method' => 'POST',
        ));

        return $this->render('LineStormPageBundle:Admin:PageType/new.html.twig', array(
            'type'   => null,
            'form'   => $form->createView(),
            'module' => $module,
        ));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function editAction($id)
    {
        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $moduleManager = $this->get('linestorm.cms.module_manager');
        $module        = $moduleManager->getModule('page');

        $modelManager = $this->get('linestorm.cms.model_manager');

        $page = $modelManager->get('page_type')->find($id);

        $form = $this->createForm('linestorm_cms_form_page_type', $page, array(
            'action' => $this->generateUrl('linestorm_cms_module_page_type_api_put_type', array('id' => $page->getId())),
            'method' => 'PUT',
        ));

        return $this->render('LineStormPageBundle:Admin:PageType/edit.html.twig', array(
            'type'      => $page,
            'form'      => $form->createView(),
            'module'    => $module,
        ));
    }
} 
