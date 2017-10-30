<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace MSBios\Voting\Resource\Doctrine\Form;

use MSBios\Voting\Resource\Doctrine\Form\Element\PollSelect;
use MSBios\Voting\Resource\Form\OptionForm as DefaultOptionForm;

/**
 * Class OptionForm
 * @package MSBios\Voting\Resource\Doctrine\Form
 */
class OptionForm extends DefaultOptionForm
{
    public function init()
    {
        parent::init();
        $this->add([
            'type' => PollSelect::class,
            'name' => 'poll'
        ]);
    }
}
