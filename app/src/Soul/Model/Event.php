<?php
namespace Soul\Model;

use Phalcon\DI;
use Phalcon\Image\Adapter\GD;
use Phalcon\Mvc\Model\Query;
use Soul\Controller\Website\EventController;
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
     * @var string
     */
    public $location;

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
     * @var integer
     */
    public $crewSize;

    /**
     * @var integer
     */
    public $seatMapId;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblEvent');

        $this->hasOne('productId', '\Soul\Model\Product', 'productId', ['alias' => 'product']);
        $this->hasOne('seatMapId', '\Soul\Model\SeatMap', 'seatMapId', ['alias' => 'seatmap']);
        $this->hasMany('eventId', '\Soul\Model\Entry', 'eventId', ['alias' => 'entries']);

    }

    /**
     * Return the current event
     *
     * @return Event
     */
    public static function getCurrent()
    {
        $event =  self::findFirst(['order' => 'startDate DESC']);
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
        $cacheDir = $this->getConfig()->application->cacheDir;

        if (is_readable($imgDir)) {

            $eventPictureDir = $imgDir . '/' . $this->systemName;
            if (file_exists($eventPictureDir) && is_readable($eventPictureDir)) {
                $fileList = Util::dirToArray($eventPictureDir);

                foreach ($fileList as $file) {

                    if (is_string($file)) {
                        $filePath = $eventPictureDir . '/' . $file;
                        $thumbName =  '/thumb_' . $file;
                        $thumbFile = $cacheDir . $thumbName;

                        $fileUrl = sprintf('%s/%s/%s', $imgUrl, $this->systemName, $file);
                        $thumbUrl = '/static/image/' . $thumbName;

                        // generate a thumnail
                        $thumbnail = new GD($filePath);
                        $thumbnail->resize(360, 360);
                        $thumbnail->save($thumbFile);


                        if (Util::isImage($filePath) && Util::isImage($thumbFile)) {
                            $files['images'][] = [
                                'url' => $fileUrl,
                                'thumb' => $thumbUrl
                            ];
                        } else {
                            $files['other'][] = $fileUrl;
                        }
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
        $entry->seat = 0.0;
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
     * @return bool
     */
    public function hasPayedForBuffet($userId)
    {
        return ($this->hasPayed($userId) && $this->getUserPayment($userId) >= ($this->getEventCost() + EventController::DINNER_OPTION_PRICE) ? true : false);
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
     * Returns a list of payed users
     *
     * @return array
     */
    public function getPayedUsers()
    {
        $users = [];
        foreach ($this->entries as $entry) {
            if ($this->hasPayed($entry->userId)) {
                $users[] = $entry->user;
            }
        }

        return $users;
    }

    /**
     * Returns a list of payed users
     *
     * @return array
     */
    public function getUsers()
    {
        $users = [];
        foreach ($this->entries as $entry) {
                $users[] = $entry->user;
        }

        return $users;
    }

    /**
     * @return bool
     */
    public function hasPassed()
    {
        return (bool) (strtotime($this->endDate) < time());
    }

    /**
     * @return SeatMap|bool
     */
    public function getSeatMap()
    {

        /** @var SeatMap $seatmap */
        $seatmap = $this->seatmap;

        $cacheDir = $this->getConfig()->application->cacheDir;
        $cachedSeatMap = sprintf('%s_seatmap.png', $this->systemName);

        if (!file_exists($cacheDir.$cachedSeatMap)) {
            file_put_contents($cacheDir.$cachedSeatMap, $seatmap->image);
        }

        $seatmap->url = sprintf('/static/%s', $cachedSeatMap);

        return $seatmap;
    }

      /**
     * @return string
     */
    public function getFullDate() 
    {
        setlocale(LC_TIME, 'nl_NL');
        $format = '%A %e %B %Y %H:%M';

        return strftime($format, strtotime($this->startDate)) . " t/m " . strftime($format, strtotime($this->endDate));        
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
            'productId' => 'productId',
            'seatMapId' => 'seatMapId',
            'crewSize' => 'crewSize',
            'location' => 'location'
        );

    }

}
