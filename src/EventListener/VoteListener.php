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
use MSBios\Voting\Resource\Doctrine\Entity\Option;
use MSBios\Voting\Resource\Doctrine\Entity\Poll;
use MSBios\Voting\Resource\Doctrine\Entity\Vote;
use MSBios\Voting\Resource\Record\VoteInterface;

/**
 * Class VoteListener
 * @package MSBios\Voting\Resource\Doctrine\EventListener
 */
class VoteListener
{
    /**
     * @param VoteInterface $entity
     * @param LifecycleEventArgs $args
     * @ORM\PreUpdate
     */
    public function onPreUpdate(VoteInterface $entity, LifecycleEventArgs $args)
    {
        /** @var ObjectManager $dem */
        $dem = $args->getObjectManager();

        /** @var QueryBuilder $qb */
        $qb = $dem->createQueryBuilder();
        $qb->update(Vote::class, 'v')
            ->join(Option::class, 'o', 'WITH', 'o.id = v.option')
            ->set('v.composition', $qb->expr()->literal('o.ponderability * v.total')) // fix it
            ->where('v.poll = :poll')
            ->setParameter('poll', $entity->getPoll())
            ->getQuery()
            ->execute();
    }

    /**
     * @param VoteInterface $entity
     * @param LifecycleEventArgs $args
     * @ORM\PostUpdate
     */
    public function onPostUpdate(VoteInterface $entity, LifecycleEventArgs $args)
    {
        /** @var ObjectManager $dem */
        $dem = $args->getObjectManager();

        /** @var Poll $poll */
        $poll = $entity->getPoll();

        /** @var int $total */
        $total = $dem->createQueryBuilder()
            ->select('SUM(v.total) as result')
            ->from(Vote::class, 'v')
            ->where('v.poll = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->getSingleScalarResult();

        /** @var float $avg */
        $avg = $dem->createQueryBuilder()
            ->select('SUM((o.ponderability * v.total)) AS result')
            ->from(Vote::class, 'v')
            ->join(Option::class, 'o', 'WITH', 'o.id = v.option')
            ->where('v.poll = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->getSingleScalarResult();

        $poll->setAvg($total ? $avg / $poll->getTotal() : 0);

        /** @var QueryBuilder $qb */
        $qb = $dem->createQueryBuilder();
        $qb->update(Poll::class, 'p')
            ->set('p.total', $qb->expr()->literal($poll->getTotal()))
            ->set('p.avg', $qb->expr()->literal($poll->getAvg()))
            ->where('p.id = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->execute();

        /** @var QueryBuilder $qb */
        $qb = $dem->createQueryBuilder();
        $qb->update(Vote::class, 'v')
            ->set('v.percent', $total ? "(100 / {$total} ) * v.total" : 0)
            ->where('v.poll = :poll')
            ->setParameter('poll', $poll)
            ->getQuery()
            ->execute();
    }
}
