<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Voting\Resource\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait TotalableTrait
 * @package MSBios\Voting\Resource\Doctrine\Entity
 */
trait TotalableTrait
{
    /**
     * @var string SUM(Vote::$total)|SUM(Option::$total)
     *
     * @ORM\Column(name="total", type="integer", length=255)
     */
    private $total = 0;

    /**
     * @return string
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
