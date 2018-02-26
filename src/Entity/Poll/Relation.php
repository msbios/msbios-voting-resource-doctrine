<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\Entity\Poll;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MSBios\Resource\Doctrine\RowStatusableAwareInterface;
use MSBios\Resource\Doctrine\RowStatusableAwareTrait;
use MSBios\Resource\Doctrine\TimestampableAwareInterface;
use MSBios\Resource\Doctrine\TimestampableAwareTrait;
use MSBios\Voting\Resource\Doctrine\Entity;
use MSBios\Voting\Resource\Record\PollInterface;
use MSBios\Voting\Resource\Record\RelationInterface;

/**
 * Class Relation
 * @package MSBios\Voting\Resource\Doctrine\Entity\Poll
 *
 * @ORM\Entity(repositoryClass="MSBios\Voting\Resource\Doctrine\Repository\Poll\RelationRepository")
 * @ORM\Table(name="vot_t_poll_relations")
 */
class Relation extends Entity implements
    TimestampableAwareInterface,
    RowStatusableAwareInterface,
    PollInterface,
    RelationInterface
{
    use TimestampableAwareTrait;
    use RowStatusableAwareTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="relation", type="string", length=200)
     */
    private $code;

    /**
     * @var Entity\Poll
     *
     * @ORM\ManyToOne(targetEntity="MSBios\Voting\Resource\Record\RelationInterface")
     * @ORM\JoinColumn(name="pollid", referencedColumnName="id")
     */
    private $poll;

    use Entity\TotalTrait;
    use Entity\AvgTrait;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return Entity\Poll
     */
    public function getPoll()
    {
        return $this->poll;
    }

    /**
     * @param $poll
     * @return $this
     */
    public function setPoll($poll)
    {
        $this->poll = $poll;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->getPoll()->getSubject();
    }

    /**
     * @return ArrayCollection
     */
    public function getOptions()
    {
        return $this->getPoll()->getOptions();
    }
}
