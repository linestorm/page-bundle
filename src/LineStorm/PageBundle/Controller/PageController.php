<?php

namespace LineStorm\PageBundle\Controller;

use LineStorm\PageBundle\Model\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Default Page frontend controller
 *
 * Class PageController
 *
 * @package LineStorm\PageBundle\Controller
 */
class PageController extends Controller
{

    public function displayAction($id)
    {
        $module = $this->get('linestorm.cms.module.page');
        $modelManager = $this->get('linestorm.cms.model_manager');
        $page = $modelManager->get('page')->find($id);

        if(!($page instanceof Page))
        {
            throw $this->createNotFoundException();
        }

        $pageType = $page->getPageType();

        return $this->render($pageType->getTemplate(), array(
            'page' => $page,
            'module' => $module,
        ));
    }
} 
