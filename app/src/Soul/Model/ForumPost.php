<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package ForumPost
 */

namespace Soul\Model;



use Phalcon\Mvc\Model\Validator\Uniqueness;
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

//    /**
//     * @var ForumCategory
//     */
//    public $category;

    /**
     * Validations and business logic
     *
     * @return bool
     */
    public function validation()
    {


        $this->validate(new Uniqueness(
            array(
                "field"   => "title",
                "message" => "Er bestaat al een topic met deze titel"
            )
        ));

        if ($this->validationHasFailed() == true) {
            return false;
        }

        return true;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('tblForumPost');
        $this->belongsTo('categoryId', '\Soul\Model\ForumCategory', 'categoryId', ['alias' => 'category']);
        $this->hasOne('userId', '\Soul\Model\User', 'userId', ['alias' => 'user']);

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
     * @param $title
     *
     * @return ForumPost
     */
    public static function findByTitle($title)
    {
        return self::findFirst(["title = '$title'"]);
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