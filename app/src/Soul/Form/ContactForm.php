<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Form;

use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Mvc\Model\Validator\Regex;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Soul\Model\User;


/**
 * Class ContactForm
 *
 * @package Soul\Form
 */
class ContactForm extends Base
{

    /**
     * Initialize the form
     */
    public function initialize()
    {

        $this->setEntity(new User());

        $email = $this->getEmailField('E-Mail');

        $realName = $this->getTextField('Uw naam', 'realName', true);
        $content = $this->getTextArea('Uw vraag/opmerking', 'content', 10, true);

        $this->add($this->getCRSF())
             ->add($email)
             ->add($content)
             ->add($realName)->add(new Submit('Versturen', [
                'class' => 'btn btn-primary btn-lg'
             ]));

    }
}