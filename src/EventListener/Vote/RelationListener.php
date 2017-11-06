<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\EventListener\Vote;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\QueryBuilder;
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

        $result = $dem->createQueryBuilder()
            ->select('SUM(vr.total) as result')
            ->from(Entity\Vote\Relation::class, 'vr')
            ->where('vr.poll = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->getSingleScalarResult();

        /** @var QueryBuilder $qb */
        $qb = $dem->createQueryBuilder();
        $qb->update(Entity\Poll\Relation::class, 'pr')
            ->set('pr.total', $qb->expr()->literal($result))
            ->where('pr.id = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->execute();

        /** @var QueryBuilder $qb */
        $qb = $dem->createQueryBuilder();
        $qb->update(Entity\Vote\Relation::class, 'vr')
            ->set('vr.percent', "(100 / {$result} ) * vr.total")
            ->where('vr.poll = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->execute();
    }
}
