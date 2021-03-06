<?php
/**
 * @category   Chirag
 * @package    Chirag_Banner
 * @author     chirag@czargroup.net
 * @copyright  This file was generated by using Module Creator provided by <developer@czargroup.net>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Chirag\Banner\Block\Adminhtml;

class Post extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'post';
        $this->_headerText = __('Post');
        $this->_addButtonLabel = __('Add New Post12345');
        parent::_construct();
    }
}
