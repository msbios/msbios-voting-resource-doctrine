<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use MSBios\Resource\Doctrine\RowStatusableAwareInterface;
use MSBios\Resource\Doctrine\RowStatusableAwareTrait;
use MSBios\Resource\Doctrine\TimestampableAwareInterface;
use MSBios\Resource\Doctrine\TimestampableAwareTrait;
use MSBios\Voting\Resource\Doctrine\Entity;

/**
 * Class Vote
 * @package MSBios\Voting\Resource\Doctrine\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="vot_t_votes",
 *     indexes={
 *          @ORM\Index(name="rowstatus", columns={"rowstatus"})}
 *     )
 * @ORM\EntityListeners({"MSBios\Voting\Resource\Doctrine\EventListener\VoteListener"})
 */
class Vote extends Entity implements
    TimestampableAwareInterface,
    RowStatusableAwareInterface
{
    use TimestampableAwareTrait;
    use RowStatusableAwareTrait;

    /**
     * @var Poll
     *
     * @ORM\ManyToOne(targetEntity="Poll")
     * @ORM\JoinColumn(name="pollid", referencedColumnName="id")
     */
    private $poll;

    /**
     * One Vote has One Option.
     *
     * @var Option
     *
     * @ORM\OneToOne(targetEntity="Option", inversedBy="vote")
     * @ORM\JoinColumn(name="optionid", referencedColumnName="id")
     */
    private $option;

    /**
     * @var integer
     *
     * @ORM\Column(name="total", type="integer", length=255)
     */
    private $total = 0;

    /**
     * @return Poll
     */
    public function getPoll()
    {
        return $this->poll;
    }

    /**
     * @param Poll $poll
     * @return $this
     */
    public function setPoll(Poll $poll)
    {
        $this->poll = $poll;
        return $this;
    }

    /**
     * @return Option
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @param Option $option
     * @return $this
     */
    public function setOption(Option $option)
    {
        $this->option = $option;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param $total
     * @return $this
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }
}
