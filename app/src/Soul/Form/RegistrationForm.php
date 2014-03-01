<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Form;

use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\StringLength;
use Soul\Model\User;


/**
 * Class RegistrationForm
 *
 * @package Soul\Form
 */
class RegistrationForm extends Base
{

    /**
     * Initialize the form
     */
    public function initialize()
    {

        $this->setEntity(new User());

        $email = $this->getEmailField('E-Mail');
        $password = $this->getPasswordField('Wachtwoord');
        $passwordRepeat = $this->getPasswordField('Wachtwoord (opnieuw)', 'passwordRepeat');

        $password->addValidator(
            new StringLength([
                'min' => 6,
                'messageMinimum' => 'Uw wachtwoord dient uit minstens 6 tekens te bestaan'
            ])
        );

        $passwordRepeat->addValidators(
            new Confirmation([
                'message' => 'De ingevulde wachtwoorden zijn niet gelijk aan elkaar',
                'with' => 'password'
            ])
        );

        $realName = $this->getTextField('Volledige naam', 'realName', true);
        $nickName = $this->getTextField('Nickname (bijnaam)', 'nickName', true);
        $telephone = $this->getTextField('Telefoonnummer', 'telephone');
        $address = $this->getTextField('Adres', 'address');
        $postalCode = $this->getTextField('Postcode', 'postalCode');
        $city = $this->getTextField('Woonplaats', 'city');

        $this->add($this->getCRSF())
             ->add($email)
             ->add($password)
             ->add($passwordRepeat)
             ->add($realName)
             ->add($nickName)
             ->add($telephone)
             ->add($address)
             ->add($postalCode)
             ->add($city);

    }
}