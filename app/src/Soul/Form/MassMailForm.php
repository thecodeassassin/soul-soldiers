<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Form;

use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Mvc\Model\ResultsetInterface;
use Phalcon\Mvc\Model\Validator\Regex;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Soul\Model\User;


/**
 * Class ContactForm
 *
 * @package Soul\Form
 */
class MassMailForm extends Base
{

    /**
     * Initialize the form
     *
     * @param array $users
     */
    public function initialize($entity, array $users)
    {

        $emails = '';

        foreach ($users as $user) {
            $emails .= sprintf('<%s> %s;', $user->realName, $user->email);
        }

        $subject = $this->getTextField('Onderwerp', 'subject', true);
        $email = $this->getTextArea('Email adressen', 'emails', 5, true)->setDefault($emails);

        $content = $this->getTextArea('Email text', 'content', 10, true);

        $this->add($this->getCRSF())
            ->add($email)
            ->add($subject)
            ->add($content)->add(new Submit('Versturen', [
                        'class' => 'btn btn-primary btn-lg'
                    ]));

    }
}