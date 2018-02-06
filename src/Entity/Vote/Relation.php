<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\Entity\Vote;

use Doctrine\ORM\Mapping as ORM;
use MSBios\Resource\Doctrine\RowStatusableAwareInterface;
use MSBios\Resource\Doctrine\RowStatusableAwareTrait;
use MSBios\Resource\Doctrine\TimestampableAwareInterface;
use MSBios\Resource\Doctrine\TimestampableAwareTrait;
use MSBios\Voting\Resource\Doctrine\Entity;

/**
 * Class Relation
 * @package MSBios\Voting\Resource\Doctrine\Entity\Vote
 *
 * @ORM\Entity
 * @ORM\Table(name="vot_t_vote_relations",
 *     indexes={
 *          @ORM\Index(name="rowstatus", columns={"rowstatus"})}
 *     )
 * @ORM\EntityListeners({"MSBios\Voting\Resource\Doctrine\EventListener\Vote\RelationListener"})
 */
class Relation extends Entity implements
    Entity\VoteInterface,
    TimestampableAwareInterface,
    RowStatusableAwareInterface,
    Entity\RelationInterface
{
    use TimestampableAwareTrait;
    use RowStatusableAwareTrait;

    /**
     * @var Entity\PollInterface
     *
     * @ORM\ManyToOne(targetEntity="MSBios\Voting\Resource\Doctrine\Entity\Poll\Relation")
     * @ORM\JoinColumn(name="relationid", referencedColumnName="id")
     */
    private $poll;

    /**
     * One Vote has One Option.
     *
     * @var Entity\OptionInterface
     *
     * @ORM\ManyToOne(targetEntity="MSBios\Voting\Resource\Doctrine\Entity\Option")
     * @ORM\JoinColumn(name="optionid", referencedColumnName="id")
     */
    private $option;

    use Entity\TotalTrait;
    use Entity\PercentTrait;

    /**
     * @return Entity\PollInterface
     */
    public function getPoll()
    {
        return $this->poll;
    }

    /**
     * @param Entity\PollInterface $poll
     * @return $this
     */
    public function setPoll(Entity\PollInterface $poll)
    {
        $this->poll = $poll;
        return $this;
    }

    /**
     * @return Entity\OptionInterface
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @param Entity\OptionInterface $option
     * @return $this
     */
    public function setOption(Entity\OptionInterface $option)
    {
        $this->option = $option;
        return $this;
    }
}
