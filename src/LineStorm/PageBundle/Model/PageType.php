<?php

namespace LineStorm\PageBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use LineStorm\Content\Model\ContentNodeInterface;

class PageType
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var ContentNodeInterface[]
     */
    protected $contentNodes;

    function __construct()
    {
        $this->contentNodes = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param ContentNodeInterface $contentNodes
     */
    public function addContentNodes(ContentNodeInterface $contentNodes)
    {
        $this->contentNodes[] = $contentNodes;
    }

    /**
     * @param ContentNodeInterface $contentNodes
     */
    public function removeContentNodes(ContentNodeInterface $contentNodes)
    {
        $this->contentNodes->removeElement($contentNodes);
    }

    /**
     * @return ContentNodeInterface[]
     */
    public function getContentNodes()
    {
        return $this->contentNodes;
    }

    /**
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }


}
