<?php
/**
 * @author Stephen "TheCodeAssassin" Hoogendijk <admin@tca0.nl>
 */

namespace Soul\Form\Intranet;

use Phalcon\Forms\Element\Date;
use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Soul\Model\Tournament;
use Soul\Form\Base;


/**
 * Class ContactForm
 *
 * @package Soul\Form
 */
class TournamentForm extends Base
{

    /**
     * Initialize the form
     */
    public function initialize()
    {

        $this->setEntity(new Tournament());

        $name = $this->getTextField('Toernooi naam', 'name', true);
        $rules = $this->getTextArea('Regels HTML', 'rules', 10, true, null, 'form-control ckeditor');
        $prizes = $this->getTextArea('Prijzen HTML', 'prizes', 10, true, null, 'form-control ckeditor');

        $type = $this->getSelect('type', Tournament::getTypes(), 'form-control', ['id' => 'typeSelect']);
        $isTeamTournament = $this->getCheckBox('isTeamTournament', 1);
        $teamSize = $this->getSelect('teamSize', [
            '' => '-- Geen Teams --',
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
            10 => 10
        ]);

        $image = $this->getFileField('imageUpload');


        $date = $this->getTextField('Datum', 'startDate', true, 'string', 'form-control', ['id' => 'startDate']);
        $date->addValidator(
          new Regex([
              'pattern' => '/\d{4}-[01]\d-[0-3]\d [0-2]\d:[0-5]\d:[0-5]\d/',
              'message' => 'De startdatum is ongeldig, het formaat is 2014-12-15 10:00:00'
          ])
        );

        $this->add($name)
             ->add($date)
             ->add($rules)
             ->add($type)
             ->add($image)
             ->add($isTeamTournament)
             ->add($teamSize)
             ->add($prizes)->add(new Submit('Opslaan', [
                'class' => 'btn btn-primary btn-lg'
             ]));

    }

}