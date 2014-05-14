<?php

namespace LineStorm\PageBundle\Doctrine\Orm;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Query;
use LineStorm\PageBundle\Model\Exception\PageSlugNonUniqueException;
use LineStorm\PageBundle\Model\Page;
use LineStorm\PageBundle\Model\PageType;
use LineStorm\TagComponentBundle\Model\Tag;

/**
 * Doctrine ORM listener updating the canonical fields and the password.
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class PageListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
        );
    }

    /**
     * @param LifecycleEventArgs $args
     *
     * @throws \LineStorm\PageBundle\Model\Exception\PageSlugNonUniqueException
     */
    public function prePersist($args)
    {
        $object = $args->getEntity();

        if($object instanceof Page)
        {
            $repo = $args->getEntityManager()->getRepository(get_class($object));
            $qb = $repo->createQueryBuilder('o');
            $qb->where('(o.slug=:slug or o.route=:route)')->setParameters(array(
                'slug'  => $object->getSlug(),
                'route' => $object->getRoute(),
            ));
            if($object->getId() > 0)
            {
                $qb->andWhere('o.id != ?1')->setParameter(1, $object->getId());
            }

            $result = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);

            if(count($result)){
                throw new PageSlugNonUniqueException($object);
            }
        }

        if ($object instanceof PageType && $object->getCreatedOn() === null) {
            $object->setCreatedOn(new \DateTime());
        }

        if ($object instanceof Tag && $object->getCreatedOn() === null) {
            $object->setCreatedOn(new \DateTime());
        }
    }
}
