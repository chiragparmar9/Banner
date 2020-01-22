<?php
namespace Chirag\Banner\Model\ResourceModel\Post;
 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'banner_id';
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Chirag\Banner\Model\Post',
            'Chirag\Banner\Model\ResourceModel\Post'
        );
    }
}