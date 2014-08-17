<?php
/**
 * @author Stephen Hoogendijk
 * @copyright Soul-Soldiers
 * @package ForumCategory
 */

namespace Soul\Model;



class ForumCategory extends Base
{
    /**
     *
     * @var integer
     */
    public $categoryId;

    /**
     *
     * @var string
     */
    public $name;

    /**
     * @var integer
     */
    public $adminOnly;


    /**
     * @return bool
     */
    public function isAdminOnly()
    {
        return (bool)$this->adminOnly;
    }

    /**
     * @param $adminOnly
     *
     * @return ForumCategory
     */
    public function setAdminOnly($adminOnly)
    {
        $this->adminOnly = ($adminOnly ? 1 : 0);
        return $this;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('tblForumCategory');
        $this->hasMany('categoryId', '\Soul\Model\ForumPost', 'categoryId', ['alias' => 'posts']);
    }

    /**
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public function getPosts()
    {
        return ForumPost::find(["categoryId = $this->categoryId", "replyId = null"]);
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'categoryId' => 'categoryId',
            'name' => 'name',
            'adminOnly' => 'adminOnly'
        );
    }
}