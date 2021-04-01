<?php

namespace Elogic\StoreLocator\Ui\Component\Form\Element;

use Elogic\StoreLocator\Model\ResourceModel\ShopCollections\ShopProductsCollection;
use Magento\Framework\App\ObjectManager;

class Multiselect extends \Magento\Ui\Component\Form\Element\MultiSelect
{
    const FIELD_NAME = 'shop_products';

    /**
     * Prepare component configuration
     *
     * @return void
     */
    public function prepare()
    {
        parent::prepare();

        $config = $this->getData('config');
        $shop_id = $this->context->getRequestParam('shop_id');

        /** @var ShopProductsCollection $selectedProductsCollection */
        $selectedProductsCollection = ObjectManager::getInstance()->create(ShopProductsCollection::class);
        $selectedProductsCollection->addFieldToFilter('shop_id', $shop_id)->addFieldToSelect('product_id')->load();

        $defaultValues = [];

        foreach ($selectedProductsCollection->getData() as $product) {
            $defaultValues[] = $product['product_id'];
        }

        if (isset($config['dataScope']) && $config['dataScope'] == self::FIELD_NAME) {
            $config['default'] = implode(',', $defaultValues);
            $this->setData('config', (array)$config);
        }

    }
}
