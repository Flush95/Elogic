<?php
namespace Elogic\StoreLocator\Ui\Component\Form\Element;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class MultiselectDataProvider implements \Magento\Framework\Data\OptionSourceInterface
{
    protected $_options;

    protected $productCollection;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $productsCollection = $this->collectionFactory->create();

        $this->productCollection = $productsCollection
                ->addAttributeToSelect('*')
                ->load();
        if (!$this->_options) {
            $this->_options = $this->productCollection->toOptionArray();
        }

        return $this->_options;
    }

    /**
     * @return array
     */
    public function getAllOptions(): array
    {
        if (!$this->_options) {
            $this->_options = $this->productCollection->toOptionArray();
        }
        return $this->_options;
    }

}
