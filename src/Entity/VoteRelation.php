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
use MSBios\Voting\Resource\Record\RelationInterface;
use MSBios\Voting\Resource\Record\VoteInterface;

/**
 * Class Relation
 * @package MSBios\Voting\Resource\Doctrine\Entity
 *
 * -ORM\Entity
 * @ORM\Table(name="vot_t_vote_relations",
 *     indexes={
 *          @ORM\Index(name="rowstatus", columns={"rowstatus"})}
 *     )
 * @ORM\EntityListeners({"MSBios\Voting\Resource\Doctrine\EventListener\VoteRelationListener"})
 * @ORM\MappedSuperclass
 */
class VoteRelation extends Entity implements
    TimestampableAwareInterface,
    RowStatusableAwareInterface,
    VoteInterface,
    RelationInterface
{
    use IdentifierAwareTrait;
    use TimestampableAwareTrait;
    use RowStatusableAwareTrait;

    /**
     * @var PollInterface
     *
     * @ORM\ManyToOne(targetEntity="MSBios\Voting\Resource\Record\PollRelation")
     * @ORM\JoinColumn(name="relationid", referencedColumnName="id")
     */
    private $poll;

    /**
     * One Vote has One Option.
     *
     * @var OptionInterface
     *
     * @ORM\ManyToOne(targetEntity="MSBios\Voting\Resource\Record\OptionInterface")
     * @ORM\JoinColumn(name="optionid", referencedColumnName="id")
     */
    private $option;

    use Entity\TotalableTrait;
    use Entity\PercentableTrait;

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
