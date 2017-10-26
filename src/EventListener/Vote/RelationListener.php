<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\EventListener\Vote;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use MSBios\Voting\Resource\Doctrine\Entity;

/**
 * Class RelationListener
 * @package MSBios\Voting\Resource\Doctrine\EventListener\Vote
 */
class RelationListener
{
    /**
     * @param Entity\Vote\Relation $entity
     * @param LifecycleEventArgs $args
     * @ORM\PostUpdate
     */
    public function onPostUpdate(Entity\Vote\Relation $entity, LifecycleEventArgs $args)
    {
        /** @var ObjectManager $dem */
        $dem = $args->getObjectManager();

        /** @var Entity\Poll\Relation $poll */
        $poll = $entity->getPoll();
        $poll->setTotal(1 + $poll->getTotal());

        $dem->merge($poll);
        $dem->flush();
    }
}
