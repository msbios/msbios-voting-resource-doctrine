<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Voting\Resource\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait AvgableTrait
 * @package MSBios\Voting\Resource\Doctrine\Entity
 */
trait AvgableTrait
{
    /**
     * @var float  // SUM(Vote::$total)|SUM(Option::$total)
     *
     * @ORM\Column(name="avg", type="float")
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
