<?php

namespace LineStorm\PageBundle\Controller\Api;

use LineStorm\CmsBundle\Controller\Api\AbstractApiController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use LineStorm\PageBundle\Model\Page;

/**
 * API class for page model
 *
 * Class PageController
 *
 * @package LineStorm\PageBundle\Controller\Api
 */
class PageController extends AbstractApiController implements ClassResourceInterface
{
    /**
     * Creates a Posy type form
     *
     * @param null|Page $entity
     *
     * @return Form
     */
    private function getForm($entity = null)
    {
        return $this->createForm('linestorm_cms_form_page', $entity);
    }

    /**
     * Get a single page
     *
     * @param $id
     *
     * @return Response
     * @throws AccessDeniedException
     * @throws NotFoundHttpException
     */
    public function getAction($id)
    {
        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $modelManager = $this->getModelManager();

        $page = $modelManager->get('page')->find($id);
        if(!($page instanceof Page))
        {
            throw $this->createNotFoundException("Page not found");
        }

        $view = View::create($page);
        return $this->get('fos_rest.view_handler')->handle($view);

    }

    /**
     * Create a new page
     *
     * @return Response
     * @throws AccessDeniedException
     */
    public function postAction()
    {
        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $modelManager = $this->getModelManager();

        $request = $this->getRequest();
        $form = $this->getForm();

        $formValues = json_decode($request->getContent(), true);

        $form->submit($formValues['linestorm_cms_form_page']);

        if ($form->isValid()) {

            $em = $modelManager->getManager();
            $now = new \DateTime();

            /** @var Page $page */
            $page = $form->getData();
            $page->setAuthor($user);
            $page->setCreatedOn($now);

            $em->persist($page);
            $em->flush();

            // update the search provider!
            $searchManager = $this->get('linestorm.cms.module.search_manager');
            $pageSearchProvider = $searchManager->get('page');
            $pageSearchProvider->index($page);

            $locationPage = array(
                'location' => $this->generateUrl('linestorm_cms_module_page_admin_edit', array( 'id' => $page->getId() ))
            );
            $location = array(
                'location' => $this->generateUrl('linestorm_cms_module_page_api_get_page', array( 'id' => $page->getId() ))
            );
            $view = View::create($locationPage, 201, array( 'location' => $location ));
        } else {
            $view = View::create($form);
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Save a page
     *
     * @param $id
     *
     * @return Response
     * @throws AccessDeniedException
     * @throws NotFoundHttpException
     */
    public function putAction($id)
    {

        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $modelManager = $this->getModelManager();

        $page = $modelManager->get('page')->find($id);
        if(!($page instanceof Page))
        {
            throw $this->createNotFoundException("Page not found");
        }

        $request = $this->getRequest();
        $form = $this->getForm($page);

        $formValues = json_decode($request->getContent(), true);

        $form->submit($formValues['linestorm_cms_form_page']);

        if ($form->isValid())
        {
            $em = $modelManager->getManager();
            $now = new \DateTime();

            /** @var Page $updatedPage */
            $updatedPage = $form->getData();
            $updatedPage->setEditedBy($user);
            $updatedPage->setEditedOn($now);

            $em->persist($updatedPage);
            $em->flush();

            // update the search provider!
            $searchManager = $this->get('linestorm.cms.module.search_manager');
            $pageSearchProvider = $searchManager->get('page');
            $pageSearchProvider->index($updatedPage);

            $view = $this->createResponse(array('location' => $this->generateUrl('linestorm_cms_module_page_api_get_page', array( 'id' => $form->getData()->getId()))), 200);
        }
        else
        {
            $view = View::create($form);
        }

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Delete a page
     *
     * @param $id
     *
     * @return Response
     * @throws AccessDeniedException
     * @throws NotFoundHttpException
     */
    public function deleteAction($id)
    {

        $user = $this->getUser();
        if (!($user instanceof UserInterface) || !($user->hasGroup('admin'))) {
            throw new AccessDeniedException();
        }

        $modelManager = $this->getModelManager();

        $page = $modelManager->get('page')->find($id);
        if(!($page instanceof Page))
        {
            throw $this->createNotFoundException("Page not found");
        }

        $em = $modelManager->getManager();

        // remove indexes
        $searchManager = $this->get('linestorm.cms.module.search_manager');
        $pageSearchProvider = $searchManager->get('page');
        $pageSearchProvider->remove($page);

        $em->remove($page);
        $em->flush();

        $view = View::create(array(
            'message'  => 'Page has been deleted',
            'location' => $this->generateUrl('linestorm_cms_module_page_admin_list'),
        ));

        return $this->get('fos_rest.view_handler')->handle($view);

    }
}
