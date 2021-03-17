<?php

namespace Elogic\AdminCrud\Model\ResourceModel\ShopCollections;

use Elogic\AdminCrud\Model\ResourceModel\ShopResource;
use Elogic\AdminCrud\Model\Shop;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'store_id';

    protected function _construct()
    {
        $this->_init(Shop::class, ShopResource::class);
    }
}
