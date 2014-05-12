<?php

namespace LineStorm\PageBundle\Twig;

use LineStorm\PageBundle\Model\Page;
use LineStorm\PageBundle\Module\PageModule;
use LineStorm\Content\Component\ComponentInterface;

/**
 * Class PageExtension
 *
 * @package LineStorm\PageBundle\Twig
 */
class PageExtension extends \Twig_Extension
{
    /**
     * @var PageModule
     */
    private $pageModule;

    /**
     * @param PageModule $pageModule
     */
    public function __construct(PageModule $pageModule)
    {
        $this->pageModule = $pageModule;
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_content_component_view', array($this, 'renderContentComponentView'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('page_route', array($this, 'cmsPageRoute')),
        );
    }

    /**
     * fetch the html for components
     *
     * @param $entities
     *
     * @return mixed
     */
    public function renderContentComponentView($entities)
    {
        foreach($this->pageModule->getComponents() as $component)
        {
            /** @var $component ComponentInterface */
            if($component->isSupported($entities))
            {
                return $component->getView($entities);
            }
        }

        return null;
    }

    /**
     * Generate a page route for a page entity
     *
     * @param string $route
     *
     * @return string
     */
    public function cmsPageRoute($route)
    {
        return $this->pageModule->getRoutePrefix().$route;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'linestorm_cms_module_page_extension';
    }
}
