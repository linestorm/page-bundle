<?php

namespace LineStorm\PageBundle\Router;

use LineStorm\CmsBundle\Model\ModelManager;
use LineStorm\CmsBundle\Module\ModuleManager;
use LineStorm\PageBundle\Model\Page;
use Symfony\Component\Config\Exception\FileLoaderLoadException;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class PageLoader extends Loader implements LoaderInterface
{
    private $loaded = false;

    private $routes;

    private $moduleManager;

    public function __construct(ModelManager $modelManager)
    {
        $this->routes = new RouteCollection();
        $this->modelManager = $modelManager;
    }

    /**
     * Search for the route in the DB
     *
     * @param mixed $resource
     * @param null  $type
     *
     * @return RouteCollection
     * @throws \RuntimeException
     */
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "Page" loader twice');
        }

        $pageRepo = $this->modelManager->get('page');
        $pages = $pageRepo->findAll();

        foreach($pages as $page){
            /** @var $page Page */
            $route = new Route($page->getRoute(), array(
                '_controller' => 'LineStormPageBundle:Page:display',
                'id' => $page->getId(),
            ));
            $this->routes->add('linestorm_cms_page_'.$page->getId(), $route);
        }
        $this->loaded = true;

        return $this->routes;
    }

    public function supports($resource, $type = null)
    {
        return 'linestorm_cms_page' === $type;
    }
} 
