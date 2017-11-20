<?php
/**
 * @access protected
 * @author Judzhin Miles <judzhin[at]gns-it.com>
 */
namespace MSBios\Voting\Resource\Doctrine\Form\Element;

use DoctrineModule\Form\Element\ObjectSelect;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use MSBios\Doctrine\ObjectManagerAwareTrait;
use MSBios\Voting\Resource\Doctrine\Entity\Poll;

/**
 * Class PollSelect
 * @package MSBios\Voting\Resource\Doctrine\Form\Element
 */
class PollSelect extends ObjectSelect implements ObjectManagerAwareInterface
{
    use ObjectManagerAwareTrait;

    public function init()
    {
        $this->getProxy()->setOptions([
            'object_manager' => $this->getObjectManager(),
            'target_class' => Poll::class,
            'property' => 'subject',
            'display_empty_item' => true,
            'empty_item_label' => '---',
        ]);
    }
}
