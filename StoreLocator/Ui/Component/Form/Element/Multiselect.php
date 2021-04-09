<?php

namespace Elogic\StoreLocator\Ui\Component\Form\Element;

use Elogic\StoreLocator\Model\ResourceModel\ShopCollections\ShopProductsCollectionFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Sanitizer;

class Multiselect extends \Magento\Ui\Component\Form\Element\MultiSelect
{
    const FIELD_NAME = 'shop_products';
    /**
     * @var ShopProductsCollectionFactory
     */
    private $collectionFactory;

    /**
     * Multiselect constructor.
     * @param ContextInterface $context
     * @param ShopProductsCollectionFactory $collectionFactory
     * @param null $options
     * @param array $components
     * @param array $data
     * @param Sanitizer|null $sanitizer
     */
    public function __construct(ContextInterface $context, ShopProductsCollectionFactory $collectionFactory, $options = null, array $components = [], array $data = [], ?Sanitizer $sanitizer = null)
    {
        parent::__construct($context, $options, $components, $data, $sanitizer);
        $this->collectionFactory = $collectionFactory;
    }

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

        $selectedProductsCollection = $this->collectionFactory->create();
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
