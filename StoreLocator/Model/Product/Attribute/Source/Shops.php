<?php
declare(strict_types=1);

namespace Elogic\StoreLocator\Model\Product\Attribute\Source;

use Elogic\StoreLocator\Model\ResourceModel\ShopCollections\CollectionFactory;

class Shops extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    protected $_options;

    protected $shopsCollection;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $collection = $this->collectionFactory->create();
        $this->shopsCollection = $collection->addFieldToSelect(['shop_name', 'shop_id'], )->load();
        foreach ($this->shopsCollection->getData() as $row) {
            $this->_options[] = ['label' => $row['shop_name'], 'value' => $row['shop_id']];
        }
        return $this->_options;
    }

    /**
     * @return array
     */
    public function getAllOptions(): array
    {
        return $this->toOptionArray();
    }
}
