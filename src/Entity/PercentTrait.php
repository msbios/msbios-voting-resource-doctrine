<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Voting\Resource\Doctrine\Entity;

/**
 * Trait PercentTrait
 * @package MSBios\Voting\Resource\Doctrine\Entity
 */
trait PercentTrait
{
    /**
     * @var integer (Option::$total/Poll::$total)*100
     *
     * --ORM\Column(name="percent", type="decimal", precision=3, scale=2)
     * @ORM\Column(name="percent", type="float")
     */
    private $percent = 0.00;

    /**
     * @return int
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param $percent
     * @return $this
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;
        return $this;
    }
}
