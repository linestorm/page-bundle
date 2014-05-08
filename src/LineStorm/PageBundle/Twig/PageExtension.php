<?php

namespace LineStorm\PageBundle\Twig;

use LineStorm\PageBundle\Module\PageModule;
use LineStorm\Content\Component\ComponentInterface;

/**
 * Class PostExtension
 *
 * @package LineStorm\PostBundle\Twig
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
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'linestorm_cms_module_page_extension';
    }
}
