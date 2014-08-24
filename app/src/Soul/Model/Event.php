<?php
namespace Soul\Model;

use Phalcon\DI;
use Phalcon\Mvc\Model\Query;
use Soul\Util;

/**
 * Class Event
 *
 * @package Soul\Model
 */
class Event extends Base
{

    /**
     *
     * @var integer
     */
    public $eventId;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $details;

    /**
     *
     * @var string
     */
    public $systemName;

    /**
     *
     * @var string
     */
    public $startDate;

    /**
     *
     * @var string
     */
    public $endDate;

    /**
     *
     * @var integer
     */
    public $maxEntries;

    /**
     *
     * @var integer
     */
    public $productId;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblEvent');

        $this->hasOne('productId', '\Soul\Model\Product', 'productId', ['alias' => 'product']);
        $this->hasMany('eventId', '\Soul\Model\Entry', 'eventId', ['alias' => 'entries']);

    }

    /**
     * Return the current event
     *
     * @return Event
     */
    public static function getCurrent()
    {
        $event =  self::findFirst(['order' => 'abs(now() - startDate) asc']);
        return $event;
    }


    /**
     * Get media of the event as an array
     *
     * @return array
     */
    public function getMedia()
    {

        $cacheKey = $this->systemName.'_media';
        $cache = $this->getCache();

        if ($cache->exists($cacheKey)) {
            return $cache->get($cacheKey);
        }

        $files = [
            'images' => [],
            'other' => []
        ];

        $imgDir = $this->getConfig()->application->mediaDir;
        $imgUrl = $this->getConfig()->application->mediaUrl;

        if (is_readable($imgDir)) {

            $eventPictureDir = $imgDir . '/' . $this->systemName;
            if (file_exists($eventPictureDir) && is_readable($eventPictureDir)) {
                $fileList = Util::dirToArray($eventPictureDir);

                foreach ($fileList as $file) {

                    $filePath = $eventPictureDir . '/' . $file;
                    $fileUrl = sprintf('%s/%s/%s', $imgUrl, $this->systemName, $file);

                    if (Util::isImage($filePath)) {
                        $files['images'][] = $fileUrl;
                    } else {
                        $files['other'][] = $fileUrl;
                    }
                }

                $cache->save($cacheKey, $files);
            }
        }

        return $files;

    }

    /**
     * @param string $systemName System name of the event
     *
     * @return Event
     */
    public static function findBySystemName($systemName)
    {
        return static::findFirstBySystemName($systemName);
    }


    /**
     * Register the user to this event
     *
     * @param int $userId Id of the user to register
     *
     * @return bool
     */
    public function registerByUserId($userId)
    {

        if ($this->hasEntry($userId)) {
            return false;
        }

        $entry = new Entry();

        $entry->eventId = $this->eventId;
        $entry->userId = (int) $userId;
        $entry->paymentId = null;
        $event = Event::findByEventId($this->eventId);

        // send an email to the user
        $this->getMail()->sendToUser(
            User::findFirstByUserId($userId),
            'Inschrijving ' . $event->name,
            'signedUp',
            compact('event')
        );

        return $entry->create();
    }

    /**
     * @param $userId
     * @return bool|Entry
     */
    public function hasEntry($userId)
    {
        if ($entry = $this->findEntryByUserIdAndEventId($userId, $this->eventId)) {

            return $entry;
        }

        return false;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function hasPayed($userId)
    {
        if ($entry = static::findEntryByUserIdAndEventId($userId, $this->eventId)) {

            if ($entry->payment) {
                return (bool) $entry->payment->confirmed;
            }

        }

        return false;

    }

    /**
     * @param $userId
     *
     * @return bool|float
     */
    public function getUserPayment($userId)
    {
        if ($entry = static::findEntryByUserIdAndEventId($userId, $this->eventId)) {

            if ($entry->payment) {
                return (float) $entry->payment->amount;
            }

        }

        return false;

    }

    /**
     * @param $userId
     * @param $eventId
     * @return \Phalcon\Mvc\Model
     */
    public static function findEntryByUserIdAndEventId($userId, $eventId)
    {
        return Entry::findFirst(["userId = $userId AND eventId = ".$eventId.""]);
    }

    /**
     * @param $eventId
     * @return Event
     */
    public static function findByEventId($eventId)
    {
        return Event::findFirst(["eventId = '$eventId'"]);
    }

    /**
     * Get amount of payments for this event
     *
     * @return int
     */
    public function getAmountPayed()
    {
        $amountPayed = 0;

        foreach ($this->entries as $entry) {

            if ($entry->payment && $entry->payment->confirmed) {
                $amountPayed += 1;
            }
        }

        return $amountPayed;
    }

    /**
     * @return float
     */
    public function getEventCost()
    {
        return (float) $this->product->cost;
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'eventId' => 'eventId',
            'name' => 'name',
            'details' => 'details',
            'systemName' => 'systemName',
            'startDate' => 'startDate',
            'endDate' => 'endDate',
            'maxEntries' => 'maxEntries',
            'productId' => 'productId'
        );
    }

}
