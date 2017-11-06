<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\Repository\Poll;

use Doctrine\ORM\EntityRepository;
use MSBios\Voting\Resource\Doctrine\Entity\PollInterface;

/**
 * Class RelationRepository
 * @package MSBios\Voting\Resource\Doctrine\Repository\Poll
 */
class RelationRepository extends EntityRepository
{
    /**
     * @param PollInterface $poll
     */
    public function findVotesBy(PollInterface $poll)
    {
        /** @var string $sql */
        $sql = 'SELECT 
                    `o`.`id`,
                    `o`.`pollid`,
                    `o`.`name`,
                    IF(`vr`.`percent` IS NULL, 0, `vr`.`percent`) AS `percent`,
                    IF(`vr`.`total` IS NULL, 0, `vr`.`total`) AS `total`
                FROM
                    `vot_t_poll_relations` AS `pr`
                        INNER JOIN
                    `vot_t_polls` AS `p` ON `pr`.`refid` = `p`.`id`
                        LEFT JOIN
                    `vot_t_options` AS `o` ON `p`.`id` = `o`.`pollid`
                        LEFT JOIN
                    `vot_t_vote_relations` AS `vr` ON `o`.`id` = `vr`.`optionid`
                        AND `pr`.`id` = `vr`.`relationid`
                WHERE
                    `pr`.`id` = :identifier 
                    AND `pr`.`reftype` = :code
                ORDER BY `o`.`priority` DESC';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute([
            'identifier' => $poll->getId(),
            'code' => $poll->getCode()
        ]);

        return $stmt->fetchAll();
    }
}
