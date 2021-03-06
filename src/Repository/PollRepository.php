<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\Repository;

use MSBios\Resource\Doctrine\EntityRepository;
use MSBios\Voting\Resource\Doctrine\Entity\Poll;

/**
 * Class PollRepository
 * @package MSBios\Voting\Resource\Doctrine\Repository
 */
class PollRepository extends EntityRepository
{
    /** @const DEFAULT_ALIAS */
    const DEFAULT_ALIAS = 'p';

    /**
     * @param mixed $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return mixed|null|object
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        return $this->createQueryBuilder('p')
            ->where('p.id = :identifier')
            ->orWhere('p.code = :identifier')
            ->setMaxResults(1)
            ->setParameter('identifier', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }


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
                    `v`.`percent`, 
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
    }
}
