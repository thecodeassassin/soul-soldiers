<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package ForumPost
 */

namespace Soul\Model;



use Phalcon\Paginator\Adapter\QueryBuilder;

class ForumPost extends Base
{

    /**
     *
     * @var integer
     */
    public $postId;

    /**
     *
     * @var integer
     */
    public $replyId;

    /**
     *
     * @var integer
     */
    public $userId;

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
     *
     * @var string
     */
    public $postDate;

    /**
     *
     * @var string
     */
    public $editDate;

    /**
     *
     * @var integer
     */
    public $viewCount;

    /**
     *
     * @var integer
     */
    public $categoryId;

    /**
     *
     * @var integer
     */
    public $isSticky;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('tblForumPost');
        $this->belongsTo('categoryId', '\Soul\Model\ForumCategory', 'categoryId', ['alias' => 'category']);


    }

    /**
     * @param $categoryId
     *
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function findByCategoryId($categoryId, $isAdmin = false)
    {
        return self::find("categoryId = $categoryId");
    }

    /**
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public function getReplies()
    {
        return self::find("replyId = $this->postId");
    }

    /**
     * @return \Phalcon\Mvc\Model
     */
    public function getLastReply()
    {
        return self::findFirst(["replyId = $this->postId", "order" => 'postDate DESC']);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'postId' => 'postId',
            'replyId' => 'replyId',
            'userId' => 'userId',
            'title' => 'title',
            'body' => 'body',
            'postDate' => 'postDate',
            'editDate' => 'editDate',
            'viewCount' => 'viewCount',
            'categoryId' => 'categoryId',
            'isSticky' => 'isSticky'
        );
    }
}