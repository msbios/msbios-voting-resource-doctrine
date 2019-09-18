<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use MSBios\Doctrine\IdentifierAwareTrait;
use MSBios\Resource\Doctrine\RowStatusableAwareInterface;
use MSBios\Resource\Doctrine\RowStatusableAwareTrait;
use MSBios\Resource\Doctrine\TimestampableAwareInterface;
use MSBios\Resource\Doctrine\TimestampableAwareTrait;
use MSBios\Voting\Resource\Doctrine\Entity;
use MSBios\Voting\Resource\Record\OptionInterface;
use MSBios\Voting\Resource\Record\PollInterface;
use MSBios\Voting\Resource\Record\VoteInterface;

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
 * @ORM\MappedSuperclass
 */
class Vote extends Entity implements
    TimestampableAwareInterface,
    RowStatusableAwareInterface,
    VoteInterface
{
    use IdentifierAwareTrait;
    use TimestampableAwareTrait;
    use RowStatusableAwareTrait;

    /**
     * @var PollInterface
     *
     * @ORM\ManyToOne(targetEntity="MSBios\Voting\Resource\Record\PollInterface")
     * @ORM\JoinColumn(name="pollid", referencedColumnName="id")
     */
    private $poll;

    use TotalableTrait;
    use PercentableTrait;

    /**
     * @var integer Vote::$total * Option::$ponderability
     *
     * @ORM\Column(name="composition", type="integer", length=255)
     */
    private $composition = 0;

    /**
     * One Vote has One Option.
     *
     * @var OptionInterface
     *
     * @ORM\OneToOne(targetEntity="MSBios\Voting\Resource\Record\OptionInterface", inversedBy="vote")
     * @ORM\JoinColumn(name="optionid", referencedColumnName="id")
     */
    private $option;

    /**
     * @return PollInterface
     */
    public function getPoll()
    {
        return $this->poll;
    }

    /**
     * @param PollInterface $poll
     * @return $this
     */
    public function setPoll(PollInterface $poll)
    {
        $this->poll = $poll;
        return $this;
    }

    /**
     * @return int
     */
    public function getComposition(): int
    {
        return $this->composition;
    }

    /**
     * @param int $composition
     */
    public function setComposition(int $composition)
    {
        $this->composition = $composition;
    }

    /**
     * @return OptionInterface
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @param OptionInterface $option
     * @return $this
     */
    public function setOption(OptionInterface $option)
    {
        $this->option = $option;
        return $this;
    }
}
