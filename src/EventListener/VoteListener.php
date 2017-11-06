<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Voting\Resource\Doctrine\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\QueryBuilder;
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

        $result = $dem->createQueryBuilder()
            ->select('SUM(v.total) as result')
            ->from(Vote::class, 'v')
            ->where('v.poll = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->getSingleScalarResult();

        /** @var QueryBuilder $qb */
        $qb = $dem->createQueryBuilder();
        $qb->update(Poll::class, 'p')
            ->set('p.total', $qb->expr()->literal($result))
            ->where('p.id = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->execute();

        /** @var QueryBuilder $qb */
        $qb = $dem->createQueryBuilder();
        $qb->update(Vote::class, 'v')
            ->set('v.percent', "(100 / {$result} ) * v.total")
            ->where('v.poll = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->execute();
    }
}