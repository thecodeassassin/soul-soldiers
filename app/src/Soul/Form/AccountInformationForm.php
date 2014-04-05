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
 * Class AccountInformationForm
 *
 * @package Soul\Form
 */
class AccountInformationForm extends Base
{

    /**
     * Initialize the form
     */
    public function initialize()
    {

        $realName = $this->getTextField('Volledige naam', 'realName', true);
        $nickName = $this->getTextField('Nickname (bijnaam)', 'nickName', true);
        $telephone = $this->getTextField('Telefoonnummer', 'telephone');
        $address = $this->getTextField('Adres', 'address');
        $city = $this->getTextField('Woonplaats', 'city');
        $postalCode = $this->getPostalCodeField('Postcode', 'postalCode');

        $this->add($realName)
             ->add($nickName)
             ->add($telephone)
             ->add($address)
             ->add($postalCode)
             ->add($city)->add(new Submit('Opslaan', [
                'class' => 'btn btn-primary btn-lg'
             ]));

    }
}