<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Form;


use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Class LoginForm
 *
 * @package Soul\Form
 */
class LoginForm extends Base
{
    /**
     * Initialize the Login Form
     */
    public function initialize()
    {
        // add the elements to the form
        $this->add($this->getCRSF())
             ->add($this->getEmailField('E-Mail'))
             ->add($this->getPasswordField('Wachtwoord'))
             ->add(new Submit('Inloggen', [
                'class' => 'btn btn-default'
        ]));
    }

}