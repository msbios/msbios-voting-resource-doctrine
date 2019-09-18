<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
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
 * Class Option
 * @package MSBios\Voting\Resource\Doctrine\Entity
 *
 * @ORM\Entity(repositoryClass="MSBios\Resource\Doctrine\EntityRepository")
 * @ORM\Table(name="vot_t_options",
 *     indexes={
 *          @ORM\Index(name="rowstatus", columns={"rowstatus"})}
 *     )
 * @Gedmo\TranslationEntity(class="MSBios\Voting\I18n\Resource\Doctrine\Entity\OptionTranslation")
 */
class Option extends Entity implements
    TimestampableAwareInterface,
    RowStatusableAwareInterface,
    OptionInterface
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

    /**
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Gedmo\Translatable
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="ponderability", type="integer", length=255)
     */
    private $ponderability = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer", length=255)
     */
    private $priority = 1;

    /**
     * One Option has One Vote.
     *
     * @var VoteInterface
     *
     * @ORM\OneToOne(targetEntity="MSBios\Voting\Resource\Record\VoteInterface", mappedBy="option")
     */
    private $vote;

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getPonderability(): int
    {
        return $this->ponderability;
    }

    /**
     * @param int $ponderability
     * @return $this
     */
    public function setPonderability(int $ponderability)
    {
        $this->ponderability = $ponderability;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param $priority
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * @return VoteInterface
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * @param VoteInterface $vote
     * @return $this
     */
    public function setVote(VoteInterface $vote)
    {
        $this->vote = $vote;
        return $this;
    }
}
