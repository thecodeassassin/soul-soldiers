<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Form;

use Phalcon\Forms\Element\Submit;

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
                'class' => (ACTIVE_MODULE == 'website' ? 'btn btn-block btn-lg btn-primary' : 'btn btn-success')
        ]));
    }

}