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
    TimestampableAwareInterface,
    RowStatusableAwareInterface,
    Entity\RelationInterface
{
    use TimestampableAwareTrait;
    use RowStatusableAwareTrait;

    /**
     * @var Entity\Poll\Relation
     *
     * @ORM\ManyToOne(targetEntity="MSBios\Voting\Resource\Doctrine\Entity\Poll\Relation")
     * @ORM\JoinColumn(name="relationid", referencedColumnName="id")
     */
    private $poll;

    /**
     * One Vote has One Option.
     *
     * @var Entity\Option
     *
     * @ORM\ManyToOne(targetEntity="MSBios\Voting\Resource\Doctrine\Entity\Option")
     * @ORM\JoinColumn(name="optionid", referencedColumnName="id")
     */
    private $option;

    use Entity\TotalTrait;

    /**
     * @var integer (Option::$total/Poll::$total)*100
     *
     * @ORM\Column(name="percent", type="integer", length=255)
     */
    private $percent = 0;

    /**
     * @return Entity\Poll\Relation
     */
    public function getPoll()
    {
        return $this->poll;
    }

    /**
     * @param Entity\Poll\Relation $poll
     * @return $this
     */
    public function setPoll(Entity\Poll\Relation $poll)
    {
        $this->poll = $poll;
        return $this;
    }

    /**
     * @return Entity\Option
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @param Entity\Option $option
     * @return $this
     */
    public function setOption(Entity\Option $option)
    {
        $this->option = $option;
        return $this;
    }

    /**
     * @return int
     */
    public function getPercent(): int
    {
        return $this->percent;
    }

    /**
     * @param int $percent
     */
    public function setPercent(int $percent)
    {
        $this->percent = $percent;
    }
}
