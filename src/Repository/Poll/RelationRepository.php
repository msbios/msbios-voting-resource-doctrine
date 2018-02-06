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
use MSBios\Voting\Resource\Doctrine\Entity\PollInterface;
use MSBios\Voting\Resource\Doctrine\Entity\Vote\Relation as VoteRelation;

/**
 * Class RelationRepository
 * @package MSBios\Voting\Resource\Doctrine\Repository\Poll
 */
class RelationRepository extends EntityRepository
{
    /**
     * @param PollInterface $poll
     * @return array
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

        // /** @var string $sql */
        // $sql = 'SELECT
        //             `o`.`id`,
        //             `o`.`pollid`,
        //             `o`.`name`,
        //             IF(`vr`.`percent` IS NULL, 0, `vr`.`percent`) AS `percent`,
        //             IF(`vr`.`total` IS NULL, 0, `vr`.`total`) AS `total`
        //         FROM
        //             `vot_t_poll_relations` AS `pr`
        //                 INNER JOIN
        //             `vot_t_polls` AS `p` ON `pr`.`pollid` = `p`.`id`
        //                 LEFT JOIN
        //             `vot_t_options` AS `o` ON `p`.`id` = `o`.`pollid`
        //                 LEFT JOIN
        //             `vot_t_vote_relations` AS `vr` ON `o`.`id` = `vr`.`optionid`
        //                 AND `pr`.`id` = `vr`.`relationid`
        //         WHERE
        //             `pr`.`id` = :identifier
        //             AND `pr`.`relation` = :code
        //          ORDER BY `o`.`priority` DESC';
        //
        // $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        // $stmt->execute([
        //     'identifier' => $poll->getId(),
        //     'code' => $poll->getCode()
        // ]);
        //
        // return $stmt->fetchAll();
    }
}
