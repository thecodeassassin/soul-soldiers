<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Form;


use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Date;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
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
     * @param string $placeholder Placeholder
     * @param string $name        Name of the field
     * @param bool   $required    Mandatory field or not
     * @param string $filter      Pass a filter
     *
     * @param string $class       CSS class(es)
     *
     * @param array  $params
     *
     * @return Text
     */
    protected function getTextField($placeholder, $name, $required = false, $filter = 'string', $class = 'form-control', array $params = [])
    {

        $params = array_merge($params, ['class' => $class, 'placeholder' => $placeholder]);
        $text = new Text($name, $params);

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
     * @param bool   $required    Mandatory field or not
     * @param string $filter      Pass a filter
     *
     * @param string $class       CSS class(es)
     *
     * @return Text
     */
    protected function getDateField($placeholder, $name, $required = false, $filter = 'string', $class = 'form-control')
    {

        $text = new Date($name, ['class' => $class, 'placeholder' => $placeholder]);

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
     * @param string $name Name of the field
     * @param int $rows Number of rows
     * @param bool $required Mandatory field or not
     * @param string $filter Pass a filter
     *
     * @param string $class
     * @return Text
     */
    protected function getTextArea($placeholder, $name, $rows = 5, $required = false, $filter = 'string', $class = 'form-control')
    {

        $text = new TextArea($name, ['class' => $class, 'placeholder' => $placeholder, 'rows' => $rows]);

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
     * @param        $name
     * @param        $options
     * @param string $class
     *
     * @param array  $params
     *
     * @return \Phalcon\Forms\Element\Select
     */
    protected function getSelect($name, $options, $class = 'form-control', array $params = [])
    {
        $params = array_merge($params, ['class' => $class]);
        return new Select($name, $options, $params);
    }

    /**
     * @param        $name
     * @param        $value
     * @param string $class
     *
     * @param array  $params
     *
     * @internal param $options
     * @return \Phalcon\Forms\Element\Select
     */
    protected function getCheckBox($name, $value, $class = '', array $params = [])
    {
        $params = array_merge($params, compact('class', 'value'));
        return new Check($name, $params);
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
            $password->setAttribute('required', '');
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