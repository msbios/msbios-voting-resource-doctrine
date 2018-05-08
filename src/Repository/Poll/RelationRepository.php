<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\Repository\Poll;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Translatable\Query\TreeWalker\TranslationWalker;
use MSBios\I18n\Doctrine\TranslationQueryTrait;
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

    use TranslationQueryTrait;

    /**
     * @param PollInterface $poll
     * @param $code
     */
    public function findOneByPollAndCode(PollInterface $poll, $code)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('pr');
        $qb->where('pr.poll = :poll')
            ->andWhere('pr.code = :code')
            ->setParameters([
                'poll' => $poll,
                'code' => $code
            ]);

        return $this->addTranslation($qb->getQuery())
            ->getOneOrNullResult();
    }

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
            ]);

        return $this->addTranslation($qb->getQuery())
            ->getResult();
    }
}
