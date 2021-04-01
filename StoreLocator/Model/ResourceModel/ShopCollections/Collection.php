<?php

namespace Elogic\StoreLocator\Model\ResourceModel\ShopCollections;

use Elogic\StoreLocator\Model\ResourceModel\ShopResource;
use Elogic\StoreLocator\Model\Shop;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'shop_id';

    protected function _construct()
    {
        $this->_init(Shop::class, ShopResource::class);
    }
}
