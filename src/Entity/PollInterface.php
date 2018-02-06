<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\Entity;

/**
 * Interface PollInterface
 * @package MSBios\Voting\Resource\Doctrine\Entity
 */
interface PollInterface
{
    /**
     * @return string
     */
    public function getCode();

    /**
     * @param $code
     * @return $this
     */
    public function setCode($code);
}
