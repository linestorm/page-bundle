<?php

namespace LineStorm\PageBundle\Module;

use LineStorm\CmsBundle\Module\AbstractModule;
use LineStorm\CmsBundle\Module\ModuleInterface;
use LineStorm\Content\Component\AbstractComponent;
use LineStorm\Content\Component\ComponentInterface;
use LineStorm\Content\Module\AbstractContentModule;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class PageModule
 * @package LineStorm\PageBundle\Module
 */
class PageModule extends AbstractContentModule
{
    protected $name = 'Page';
    protected $id = 'page';

    protected $routePrefix;

    /**
     * @param mixed $routePrefix
     */
    public function setRoutePrefix($routePrefix)
    {
        $this->routePrefix = $routePrefix;
    }

    /**
     * @return mixed
     */
    public function getRoutePrefix()
    {
        return $this->routePrefix;
    }

    /**
     * Returns the navigation array
     *
     * @return array
     */
    public function getNavigation()
    {
        return array(
            'View Pages' => array('linestorm_cms_module_page_admin_page_list', array()),
            'View Page Types' => array('linestorm_cms_module_page_admin_page_type_list', array()),
        );
    }

    /**
     * The route to load as 'home'
     *
     * @return string
     */
    public function getHome()
    {
        return 'linestorm_cms_module_page_admin_list';
    }

    /**
     * Add routes to the router
     * @param Loader $loader
     * @return RouteCollection
     */
    public function addRoutes(Loader $loader)
    {
        $moduleRoutes = $loader->import('@LineStormPageBundle/Resources/config/routing.yml', 'yaml');

        // import all the component routes
        foreach ($this->components as $component) {
            $routes = $component->getRoutes($loader);
            if ($routes instanceof RouteCollection) {
                $moduleRoutes->addCollection($routes);
            }
        }

        return $moduleRoutes;
    }

    /**
     * Add routes to the router
     * @param Loader $loader
     * @return RouteCollection
     */
    public function addAdminRoutes(Loader $loader)
    {
        $moduleRoutes = $loader->import('@LineStormPageBundle/Resources/config/routing/admin.yml', 'yaml');

        // import all the component routes
        foreach ($this->components as $component) {
            $routes = $component->getAdminRoutes($loader);
            if ($routes instanceof RouteCollection) {
                $moduleRoutes->addCollection($routes);
            }
        }

        return $moduleRoutes;
    }
} 
