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
     * @param string $placeholder Placeholder
     * @param string $name        Name of the field
     * @param bool   $required    Mandatory field or not
     *
     * @return Text
     */
    protected function getTextField($placeholder, $name, $required = false)
    {

        $text = new Text($name, ['class' => 'form-control', 'placeholder' => $placeholder]);

        if ($required) {
            $text->addValidator(
                new PresenceOf([
                    'message' => sprintf('% is verplicht', $placeholder)
                ])
            );
        }

        return $text;
    }


    /**
     * @param string $placeholder Placeholder
     * @param string $name        Name of the field
     *
     * @return Text
     */
    protected function getEmailField($placeholder, $name = 'email')
    {
        $email = $this->getTextField($placeholder, $name);
        $email->addValidators([
                new PresenceOf([
                    'message' => 'Email is verplicht'
                ]),
                new Email([
                    'message' => 'Ongeldig e-mail adres'
                ])
            ]);

        return $email;
    }

    /**
     * @param string $placeholder Placeholder
     * @param string $name        Name of the field
     *
     * @return Password
     */
    protected function getPasswordField($placeholder, $name = 'password')
    {
        $password = new Password($name, ['class' => 'form-control', 'placeholder' => $placeholder]);

        $password->addValidator(new PresenceOf(array(
            'message' => 'Wachtwoord is verplicht'
        )));

        return $password;
    }
}