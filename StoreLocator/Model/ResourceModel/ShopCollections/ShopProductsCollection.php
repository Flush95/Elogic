<?php
declare(strict_types=1);
namespace Elogic\StoreLocator\Model\ResourceModel\ShopCollections;

use Elogic\StoreLocator\Model\ResourceModel\ShopProductsResource;
use Elogic\StoreLocator\Model\ShopProducts;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class ShopProductsCollection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(ShopProducts::class, ShopProductsResource::class);
    }
}
