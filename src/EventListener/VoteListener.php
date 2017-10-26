<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Voting\Resource\Doctrine\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use MSBios\Voting\Resource\Doctrine\Entity\Vote;

/**
 * Class VoteListener
 * @package MSBios\Voting\Resource\Doctrine\EventListener
 */
class VoteListener
{
    /**
     * @param Vote $entity
     * @param LifecycleEventArgs $args
     * @ORM\PostUpdate
     */
    public function onPostUpdate(Vote $entity, LifecycleEventArgs $args)
    {
        r($entity);
        die();
    }
}
