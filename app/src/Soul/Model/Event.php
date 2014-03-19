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
     * @return \Phalcon\Mvc\Model
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

        return $entry->create();
    }

    /**
     * @param $userId
     * @return bool
     */
    public function hasEntry($userId)
    {
        if ($entry = $this->findByUserIdAndSystemName($userId, $this->systemName)) {

            return true;
        }

        return false;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function hasPayed($userId)
    {
        if ($entry = $this->findByUserIdAndSystemName($userId, $this->systemName)) {

            if ($entry->payment) {
                return (bool) $entry->payment->confirmed;
            }

        }

        return false;

    }

    /**
     * @param $userId
     * @param $systemName
     * @return \Phalcon\Mvc\Model
     */
    public function findByUserIdAndSystemName($userId, $systemName)
    {

        return Entry::findFirst(["userId = '$userId'", "systemName = '".$systemName."'"]);
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
