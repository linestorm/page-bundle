<?php

namespace LineStorm\PageBundle\Controller\Api;

use LineStorm\CmsBundle\Controller\Api\AbstractApiController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use LineStorm\PageBundle\Model\PageType;

/**
 * API class for page type model
 *
 * Class PageTypeController
 *
 * @package LineStorm\PageBundle\Controller\Api
 *
 * @RouteResource("page/type")
 */
class PageTypeController extends AbstractApiController implements ClassResourceInterface
{
    /**
     * Creates a Page Type type form
     *
     * @param null|PageType $entity
     *
     * @return Form
     */
    private function getForm($entity = null)
    {
        return $this->createForm('linestorm_cms_form_page_type', $entity);
    }

    /**
     * Get a single page type
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

        $page = $modelManager->get('page_type')->find($id);
        if(!($page instanceof PageType))
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

        $form->submit($formValues['linestorm_cms_form_page_type']);

        if ($form->isValid()) {

            $em = $modelManager->getManager();
            $now = new \DateTime();

            /** @var PageType $page */
            $page = $form->getData();

            $page->setCreatedBy($user);
            $page->setCreatedOn($now);
            $em->persist($page);
            $em->flush();

            $locationPage = array(
                'location' => $this->generateUrl('linestorm_cms_module_page_admin_page_type_edit', array( 'id' => $page->getId() ))
            );
            $location = array(
                'location' => $this->generateUrl('linestorm_cms_module_page_type_api_get_type', array( 'id' => $page->getId() ))
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

        $page = $modelManager->get('page_type')->find($id);
        if(!($page instanceof PageType))
        {
            throw $this->createNotFoundException("Page not found");
        }

        $request = $this->getRequest();
        $form = $this->getForm($page);

        $formValues = json_decode($request->getContent(), true);

        $form->submit($formValues['linestorm_cms_form_page_type']);

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
            /*$searchManager = $this->get('linestorm.cms.module.search_manager');
            $pageSearchProvider = $searchManager->get('page');
            $pageSearchProvider->index($updatedPage);*/

            $view = $this->createResponse(array('location' => $this->generateUrl('linestorm_cms_module_page_api_get_page', array( 'id' => $updatedPage->getId()))), 200);
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

        $page = $modelManager->get('page_type')->find($id);
        if(!($page instanceof PageType))
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
            'location' => $this->generateUrl('linestorm_cms_module_page_admin_page_type_list'),
        ));

        return $this->get('fos_rest.view_handler')->handle($view);

    }
}
