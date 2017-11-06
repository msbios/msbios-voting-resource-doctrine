<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use MSBios\Voting\Resource\Doctrine\Entity\Poll;

/**
 * Class PollRepository
 * @package MSBios\Voting\Resource\Doctrine\Repository
 */
class PollRepository extends EntityRepository
{
    /**
     * @todo: => $queryBuilder
     * @param Poll $poll
     * @return array
     */
    public function findVotesBy(Poll $poll)
    {
        /** @var string $sql */
        $sql = 'SELECT 
                    `o`.`id`, 
                    `o`.`pollid`, 
                    `o`.`name`, 
                    `o`.`percent`, 
                    IF(`v`.`total` IS NULL, 0, `v`.`total`) AS `total` 
                FROM 
                    `vot_t_options` AS `o` 
                        LEFT JOIN 
                    `vot_t_votes` AS `v` ON `o`.`id` = `v`.`optionid` 
                        AND `o`.`pollid` = `v`.`pollid`
                WHERE 
                    `o`.`pollid` = :identifier
                ORDER BY `o`.`priority` DESC';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute(['identifier' => $poll->getId()]);

        return $stmt->fetchAll();
        return [];
    }
}
