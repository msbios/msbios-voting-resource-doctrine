<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Voting\Resource\Doctrine\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use MSBios\Voting\Resource\Doctrine\Entity\Poll;
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
     */
    public function onPreUpdate(Vote $entity, LifecycleEventArgs $args)
    {
        $entity->setComposition(
            $entity->getTotal() * $entity->getOption()->getPonderability()
        );
    }

    /**
     * @param Vote $entity
     * @param LifecycleEventArgs $args
     * @ORM\PostUpdate
     */
    public function onPostUpdate(Vote $entity, LifecycleEventArgs $args)
    {
        /** @var ObjectManager $dem */
        $dem = $args->getObjectManager();

        /** @var Poll $poll */
        $poll = $entity->getPoll();
        $poll->setTotal(1 + $poll->getTotal());

        $dem->merge($poll);
        $dem->flush();
    }
}
