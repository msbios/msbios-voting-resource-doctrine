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
use MSBios\Voting\Resource\Doctrine\Entity;
use MSBios\Voting\Resource\Record\VoteInterface;

/**
 * Class VoteRelationListener
 * @package MSBios\Voting\Resource\Doctrine\EventListener
 */
class VoteRelationListener
{
    /**
     * @param VoteInterface $vote
     * @param LifecycleEventArgs $args
     */
    public function onPostUpdate(VoteInterface $vote, LifecycleEventArgs $args)
    {
        /** @var ObjectManager $dem */
        $dem = $args->getObjectManager();

        /** @var Entity\PollRelation $poll */
        $poll = $vote->getPoll();

        /** @var int $total */
        $total = $dem->createQueryBuilder()
            ->select('SUM(vr.total) as result')
            ->from(Entity\VoteRelation::class, 'vr')
            ->where('vr.poll = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->getSingleScalarResult();

        $poll->setTotal($total);

        /** @var float $avg */
        $avg = $dem->createQueryBuilder()
            ->select('SUM((o.ponderability * vr.total)) AS result')
            ->from(Entity\VoteRelation::class, 'vr')
            ->join(Entity\Option::class, 'o', 'WITH', 'o.id = vr.option')
            ->where('vr.poll = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->getSingleScalarResult();

        $poll->setAvg($total ? $avg / $poll->getTotal() : 0);

        /** @var QueryBuilder $qb */
        $qb = $dem->createQueryBuilder();
        $qb->update(Entity\PollRelation::class, 'pr')
            ->set('pr.total', $qb->expr()->literal($poll->getTotal()))
            ->set('pr.avg', $qb->expr()->literal($poll->getAvg()))
            ->where('pr.id = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->execute();

        /** @var QueryBuilder $qb */
        $qb = $dem->createQueryBuilder();
        $qb->update(Entity\VoteRelation::class, 'vr')
            ->set('vr.percent', $total ? "(100 / {$total} ) * vr.total" : 0)
            ->where('vr.poll = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->execute();
    }
}
