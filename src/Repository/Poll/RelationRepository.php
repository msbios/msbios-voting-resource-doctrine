<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\Repository\Poll;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use MSBios\Voting\Resource\Doctrine\Entity\Option;
use MSBios\Voting\Resource\Doctrine\Entity\Poll;
use MSBios\Voting\Resource\Doctrine\Entity\Vote\Relation as VoteRelation;
use MSBios\Voting\Resource\Record\PollInterface;

/**
 * Class RelationRepository
 * @package MSBios\Voting\Resource\Doctrine\Repository\Poll
 */
class RelationRepository extends EntityRepository
{
    /**
     * @param PollInterface $poll
     * @return mixed
     */
    public function findVotesBy(PollInterface $poll)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('o.id', 'o.name')
            ->addSelect("CASE WHEN (vr.percent IS NULL) THEN 0 ELSE vr.percent END AS percent")
            ->addSelect("CASE WHEN (vr.total IS NULL) THEN 0 ELSE vr.total END AS total")
            ->from(Option::class, 'o')
            ->join(Poll::class, 'p', Join::WITH, 'o.poll = p')
            ->leftJoin(Poll\Relation::class, 'pr', Join::WITH, 'p = pr.poll')
            ->leftJoin(VoteRelation::class, 'vr', Join::WITH, 'o = vr.option AND pr = vr.poll')
            ->where('pr.id = :identifier')
            ->andWhere('pr.code = :code')
            ->orderBy('o.priority', 'DESC')
            ->setParameters([
                'identifier' => $poll->getId(),
                'code' => $poll->getCode()
            ])
        ;

        return $qb->getQuery()->getResult();
    }
}
