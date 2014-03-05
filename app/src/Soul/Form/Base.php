<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Form;


use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;

/**
 * Class Base
 *
 * @package Soul\Form
 */
abstract class Base extends Form
{

    /**
     * Get a CSRF field for protection against Cross Site Request Forgery
     *
     * @return Hidden
     */
    protected  function getCRSF()
    {
        $csrf = new Hidden('csrf');

        $csrf->addValidator(new Identical([
            'value' => $this->security->getSessionToken(),
            'message' => 'Validatie mislukt, probeer het nogmaals.'
        ]));

        return $csrf;
    }

    /**
     * @param string      $placeholder Placeholder
     * @param string      $name        Name of the field
     * @param bool        $required    Mandatory field or not
     * @param string      $filter      Pass a filter
     *
     * @return Text
     */
    protected function getTextField($placeholder, $name, $required = false, $filter = 'string')
    {

        $text = new Text($name, ['class' => 'form-control', 'placeholder' => $placeholder]);

        if ($required) {
            $text->addValidator(
                new PresenceOf([
                    'message' => sprintf('%s is verplicht', $placeholder)
                ])
            );
            $text->setAttribute('required', '');
        }

        if (!empty($filter)) {
            $text->addFilter($filter);
        }

        return $text;
    }


    /**
     * @param string $placeholder Placeholder
     * @param string $name        Name of the field
     * @param bool   $required    Field required or not
     *
     * @return Text
     */
    protected function getEmailField($placeholder, $name = 'email', $required = true)
    {
        $email = $this->getTextField($placeholder, $name, $required, 'email');
        $email->addValidator(
            new Email([
                'message' => 'Ongeldig e-mail adres'
            ])
        );

        return $email;
    }

    /**
     * @param string $placeholder Placeholder
     * @param string $name        Name of the field
     * @param bool   $required    Field required or not
     * @param string $filter      Optional filter
     *
     * @return Password
     */
    protected function getPasswordField($placeholder, $name = 'password', $required = true, $filter = 'string')
    {
        $password = new Password($name, ['class' => 'form-control', 'placeholder' => $placeholder]);

        if ($required) {
            $password->addValidator(new PresenceOf(array(
                'message' => 'Wachtwoord is verplicht'
            )));
        }

        if (!empty($filter)) {
            $password->addFilter($filter);
        }

        return $password;
    }

    /**
     * @param string $placeholder Placeholder
     * @param string $name        Name of the field
     * @param bool   $required    Field required or not
     * @param string $filter      Optional filter
     *
     * @return Text
     */
    protected function getPostalCodeField($placeholder, $name = 'postalCode', $required = false, $filter = 'string')
    {
        $postalCode = $this->getTextField($placeholder, $name, $required, $filter);

        $postalCode->addValidator(new Regex([
            'pattern' => '/^$|^[1-9][0-9]{3}\s?[a-zA-Z]{2}$/',
            'message' => 'De opgegeven postcode is ongeldig'
        ]));

        return $postalCode;
    }
}