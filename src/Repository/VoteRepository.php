<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Voting\Resource\Doctrine\Repository;

use MSBios\Resource\Doctrine\EntityRepository;
use MSBios\Voting\Resource\Doctrine\Entity\Poll;

/**
 * Class VoteRepository
 * @package MSBios\Voting\Resource\Doctrine\Repository
 */
class VoteRepository extends EntityRepository
{
    /** @const DEFAULT_ALIAS */
    const DEFAULT_ALIAS = 'v';

    /**
     * @param Poll $poll
     */
    public function findVotesBy(Poll $poll)
    {

        // SELECT
        //     `o`.`id`,
        //     `o`.`pollid`,
        //     `o`.`name`,
        //     `o`.`total`,
        //     `o`.`percent`,
        //     IF(`v`.`total` IS NULL, 0, `v`.`total`) AS `vote`
        // FROM
        //     `vot_t_options` AS `o`
        //         LEFT JOIN
        //     `vot_t_votes` AS `v` ON `o`.`id` = `v`.`optionid`
        //     AND `o`.`pollid` = `v`.`pollid`
        // WHERE
        //     `o`.`pollid` = :identifier;
    }
}
