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

        /** @var int $total */
        $total = $dem->createQueryBuilder()
            ->select('SUM(vr.total) as result')
            ->from(Entity\Vote\Relation::class, 'vr')
            ->where('vr.poll = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->getSingleScalarResult();

        $poll->setTotal($total);

        /** @var float $avg */
        $avg = $dem->createQueryBuilder()
            ->select('SUM((o.ponderability * vr.total)) AS result')
            ->from(Entity\Vote\Relation::class, 'vr')
            ->join(Entity\Option::class, 'o', 'WITH', 'o.id = vr.option')
            ->where('vr.poll = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->getSingleScalarResult();

        $poll->setAvg($total ? $avg / $poll->getTotal() : 0);

        /** @var QueryBuilder $qb */
        $qb = $dem->createQueryBuilder();
        $qb->update(Entity\Poll\Relation::class, 'pr')
            ->set('pr.total', $qb->expr()->literal($poll->getTotal()))
            ->set('pr.avg', $qb->expr()->literal($poll->getAvg()))
            ->where('pr.id = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->execute();

        /** @var QueryBuilder $qb */
        $qb = $dem->createQueryBuilder();
        $qb->update(Entity\Vote\Relation::class, 'vr')
            ->set('vr.percent', "(100 / {$total} ) * vr.total")
            ->where('vr.poll = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->execute();
    }
}
