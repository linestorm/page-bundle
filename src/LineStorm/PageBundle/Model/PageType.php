<?php

namespace LineStorm\PageBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use LineStorm\Content\Model\ContentNodeInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Model for a Page Type
 *
 * Class PageType
 *
 * @package LineStorm\PageBundle\Model
 */
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
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * @var \DateTime
     */
    protected $editedOn;

    /**
     * @var UserInterface
     */
    protected $createdBy;

    /**
     * @var UserInterface
     */
    protected $editedBy;

    /**
     * @var ContentNodeInterface[]
     */
    protected $contentNodes;

    /**
     * Setup model
     */
    function __construct()
    {
        $this->createdOn = new \DateTime();
        $this->contentNodes = new ArrayCollection();
    }

    /**
     * @return string
     */
    function __toString()
    {
        return "{$this->name} ({$this->controller}:{$this->action})";
    }

    public function getFullControllerAction()
    {
        return "{$this->controller}:{$this->action}";
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

    /**
     * @param UserInterface $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return UserInterface
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param \DateTime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param UserInterface $editedBy
     */
    public function setEditedBy(UserInterface $editedBy)
    {
        $this->editedBy = $editedBy;
    }

    /**
     * @return UserInterface
     */
    public function getEditedBy()
    {
        return $this->editedBy;
    }

    /**
     * @param \DateTime $editedOn
     */
    public function setEditedOn(\DateTime $editedOn)
    {
        $this->editedOn = $editedOn;
    }

    /**
     * @return \DateTime
     */
    public function getEditedOn()
    {
        return $this->editedOn;
    }


}
