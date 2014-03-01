<?php

namespace Soul\Model;

/**
 * Class Product
 *
 * @package Soul\Model
 */
class Product extends Base
{

    /**
     *
     * @var integer
     */
    public $productId;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var double
     */
    public $cost;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('tblProduct');

    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'productId' => 'productId',
            'title' => 'title',
            'description' => 'description',
            'cost' => 'cost'
        );
    }

}
