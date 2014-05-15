<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Form;

use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Submit;
use Phalcon\Mvc\Model\Validator\Regex;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Soul\Model\User;


/**
 * Class NewsAddForm
 *
 * @package Soul\Form
 */
class NewsAddForm extends Base
{

    /**
     * Initialize the form
     */
    public function initialize()
    {

        $editor = $this->getTextArea('Content', 'body', 5, true, 'string');
        $title = $this->getTextField('Titel', 'title', true, 'string');

        $this->add($editor)
            ->add($title)
            ->add(new Submit('Aanmaken', [
                'class' => 'btn btn-primary btn-lg'
             ]));

    }
}