<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use MSBios\Doctrine\IdentifierAwareTrait;
use MSBios\Resource\Doctrine\RowStatusableAwareInterface;
use MSBios\Resource\Doctrine\RowStatusableAwareTrait;
use MSBios\Resource\Doctrine\TimestampableAwareInterface;
use MSBios\Resource\Doctrine\TimestampableAwareTrait;
use MSBios\Voting\Resource\Doctrine\Entity;
use MSBios\Voting\Resource\Record\PollInterface;

/**
 * Class Poll
 * @package MSBios\Voting\Resource\Doctrine\Entity
 *
 * @ORM\Entity(repositoryClass="MSBios\Voting\Resource\Doctrine\Repository\PollRepository")
 * @ORM\Table(name="vot_t_polls",
 *     indexes={
 *          @ORM\Index(name="rowstatus", columns={"rowstatus"})}
 *     )
 * @Gedmo\TranslationEntity(class="MSBios\Voting\I18n\Resource\Doctrine\Entity\PollTranslation")
 */
class Poll extends Entity implements
    TimestampableAwareInterface,
    RowStatusableAwareInterface,
    PollInterface
{
    use IdentifierAwareTrait;
    use TimestampableAwareTrait;
    use RowStatusableAwareTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, unique=true, nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     * @Gedmo\Translatable
     */
    private $subject;

    use TotalableTrait;
    use AvgableTrait;

    /**
     * One Poll has Many Options.
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MSBios\Voting\Resource\Record\OptionInterface", mappedBy="poll")
     */
    private $options;

    /**
     * Poll constructor.
     */
    public function __construct()
    {
        $this->options = new ArrayCollection;
    }

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
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param ArrayCollection $options
     * @return $this
     */
    public function setOptions(ArrayCollection $options)
    {
        $this->options = $options;
        return $this;
    }
}
