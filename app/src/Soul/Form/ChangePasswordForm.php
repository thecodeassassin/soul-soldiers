<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Form;

use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\StringLength;

/**
 * Class ChangePasswordForm
 *
 * @package Soul\Form
 */
class ChangePasswordForm extends Base
{
    /**
     * Initialize the Login Form
     */
    public function initialize()
    {

        $password = $this->getPasswordField('Wachtwoord');
        $passwordRepeat = $this->getPasswordField('Wachtwoord (opnieuw)', 'passwordRepeat');

        $password->addValidator(
            new StringLength([
                'min' => 6,
                'messageMinimum' => 'Uw wachtwoord dient uit minstens 6 tekens te bestaan'
            ])
        );

        $passwordRepeat->addValidator(
            new Confirmation([
                'message' => 'De ingevulde wachtwoorden zijn niet gelijk aan elkaar',
                'with' => 'password'
            ])
        );


        // add the elements to the form
        $this->add($this->getCRSF())
             ->add($password)
             ->add($passwordRepeat)
             ->add(new Submit('Opslaan', ['class' => 'btn btn-primary btn-lg']));


    }

    public function addCurrentPassword()
    {
        $current = $this->getPasswordField('Huidige wachtwoord', 'currentPassword');

        $this->add($current);
    }

}