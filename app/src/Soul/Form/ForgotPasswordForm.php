<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Form;

use Phalcon\Forms\Element\Submit;

/**
 * Class ForgotPasswordForm
 *
 * @package Soul\Form
 */
class ForgotPasswordForm extends Base
{
    /**
     * Initialize the Login Form
     */
    public function initialize()
    {
        // add the elements to the form
        $this->add($this->getCRSF())
             ->add($this->getEmailField('E-Mail'))
             ->add(new Submit('Nieuw wachtwoord opvragen', ['class' => 'btn btn-block btn-lg btn-primary']));


    }

}