<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Voting\Resource\Doctrine\Entity;

/**
 * Trait AvgTrait
 * @package MSBios\Voting\Resource\Doctrine\Entity
 */
trait AvgTrait
{
    /**
     * @var float  // SUM(Vote::$total)|SUM(Option::$total)
     *
     * @ORM\Column(name="avg", type="decimal", precision=3, scale=2)
     */
    private $avg = 0.00;

    /**
     * @return float
     */
    public function getAvg(): float
    {
        return $this->avg;
    }

    /**
     * @param float $avg
     */
    public function setAvg(float $avg)
    {
        $this->avg = $avg;
    }
}
