<?php

namespace LineStorm\PageBundle\Model\Exception;

/**
 * Exception for when a page entity is persisted with a non-unique slug
 *
 * Class PageSlugNonUniqueException
 *
 * @package LineStorm\PageBundle\Model\Exception
 */
class PageSlugNonUniqueException extends \Exception
{
    private $object;

    /**
     * @param string     $object
     * @param \Exception $previous
     */
    public function __construct($object, \Exception $previous = null)
    {
        parent::__construct("The provided slug is not unique", null, $previous);
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

} 
