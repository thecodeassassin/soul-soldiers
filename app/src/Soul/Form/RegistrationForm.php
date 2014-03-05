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

        $passwordRepeat->addValidator(
            new Confirmation([
                'message' => 'De ingevulde wachtwoorden zijn niet gelijk aan elkaar',
                'with' => 'password'
            ])
        );

        $terms = new Check('terms', ['value' => 'yes']);
        $terms->addValidator(new Identical([
            'value'   => 'yes',
            'message' => 'U dient akkoord te gaan met de algemene voorwaarden.'
        ]));

        $realName = $this->getTextField('Volledige naam', 'realName', true);
        $nickName = $this->getTextField('Nickname (bijnaam)', 'nickName', true);
        $telephone = $this->getTextField('Telefoonnummer', 'telephone');
        $address = $this->getTextField('Adres', 'address');
        $city = $this->getTextField('Woonplaats', 'city');
        $postalCode = $this->getPostalCodeField('Postcode', 'postalCode');

        $this->add($this->getCRSF())
             ->add($email)
             ->add($password)
             ->add($passwordRepeat)
             ->add($realName)
             ->add($nickName)
             ->add($telephone)
             ->add($address)
             ->add($postalCode)
             ->add($terms)
             ->add($city)->add(new Submit('Registeren', [
                'class' => 'btn btn-primary btn-lg'
             ]));

    }
}