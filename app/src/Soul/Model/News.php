<?php
namespace Soul\Model;

use Phalcon\Mvc\Model\ResultsetInterface;

/**
 * Class News
 */
class News extends Base
{

    /**
     *
     * @var integer
     */
    public $newsId;

    /**
     *
     * @var string
     */
    public $module;

    /**
     *
     * @var string
     */
    public $published;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $body;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblNews');

    }

    /**
     *
     */
    public function afterFetch()
    {
        $this->published = date('d-m-Y H:i', strtotime($this->published));
    }

    /**
     *
     */
    public function beforeSave()
    {
        $this->published = date('Y-m-d H:i:s', strtotime($this->published));
    }

    /**
     * Find news items by module
     *
     * @param $module
     * @return ResultSetInterface
     */
    public static function getByModule($module)
    {
        return static::find(
            [
               'module = \''.$module.'\'',
               'order' => 'published DESC'
            ]
        );
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'newsId' => 'newsId',
            'module' => 'module',
            'published' => 'published',
            'title' => 'title',
            'body' => 'body'
        );
    }

}
