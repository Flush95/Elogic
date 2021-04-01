<?php
declare(strict_types=1);
namespace Elogic\StoreLocator\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ShopProductsResource extends AbstractDb
{

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('shop_products', 'entity_id');
    }
}
