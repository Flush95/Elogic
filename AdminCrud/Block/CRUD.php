<?php

declare(strict_types=1);

namespace Elogic\AdminCrud\Block;

use Elogic\AdminCrud\Model\ResourceModel\ShopCollections\Collection;
use Magento\Framework\View\Element\Template;

class CRUD extends Template
{

    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * CRUD constructor.
     * @param Template\Context $context
     * @param Collection $collection
     * @param array $data
     */
    public function __construct(Template\Context $context, Collection $collection, array $data = [])
    {
        parent::__construct($context, $data);
        $this->collection = $collection;
    }

    public function getAllStores(): Collection
    {
        return $this->collection;
    }
}
