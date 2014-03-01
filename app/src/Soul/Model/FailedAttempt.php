<?php
namespace Soul\Model;

use Phalcon\Mvc\Model\Behavior\Timestampable;
use Soul\Util;
use Soul\Auth\Exception as AuthException;

/**
 * Class FailedAttempt
 *
 * @package Soul\Model
 */
class FailedAttempt extends Base
{

    /**
     *
     * @var integer
     */
    public $attemptId;

    /**
     *
     * @var string
     */
    public $ipaddress;

    /**
     *
     * @var string
     */
    public $attemptinfo;

    /**
     *
     * @var integer
     */
    public $count;

    /**
     *
     * @var string
     */
    public $timestamp;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblFailedAttempt');

        $this->addBehavior(new Timestampable([
            'beforeCreate' => [
                'field' => 'timestamp',
                'format' => 'Y-m-d H:i:s'
            ],
            'beforeUpdate' => [
                'field' => 'timestamp',
                'format' => 'Y-m-d H:i:s'
            ]
        ]));

    }

    /**
     * Add a failed login attempt
     *
     * @param string $emailAddress User's email address
     *
     * @throws \Soul\Auth\Exception
     */
    public static function add($emailAddress)
    {
        $ipAddress = Util::getClientIp();

        $entryExists = static::findFirst("ipaddress='$ipAddress'");

        // failed login entry exists, add one to the counter
        if ($entryExists instanceof self) {

            $minutesLeft = round((strtotime($entryExists->timestamp) + 300 - time()) / 60);
            if ($entryExists->count == 3 && $minutesLeft > 0) {
                throw new AuthException(sprintf(
                    'U heeft te vaak proberen in te loggen, U kunt het over %d minuten nog een keer proberen.',
                    $minutesLeft
                ));
            } elseif ($entryExists->count == 3 && $minutesLeft <= 0) {

                // reset the counter if the "time of ban" is passed
                $entryExists->count = 0;
            }

            $entryExists->count += 1;

            $entryExists->save();

        } else {

            // create a new failed login entry
            $newEntry = new self();

            $newEntry->count = 1;
            $newEntry->ipaddress = $ipAddress;
            $newEntry->attemptinfo = serialize([
               'userAgent' => $_SERVER['HTTP_USER_AGENT'],
               'referrer' => $_SERVER['HTTP_REFERER'],
               'emailAddress' => $emailAddress
            ]);
            $newEntry->save();
        }
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'attemptId' => 'attemptId',
            'ipaddress' => 'ipaddress',
            'attemptinfo' => 'attemptinfo',
            'count' => 'count',
            'timestamp' => 'timestamp'
        );
    }

}
