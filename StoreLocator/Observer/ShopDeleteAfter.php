<?php
declare(strict_types=1);

namespace Elogic\StoreLocator\Observer;

use Exception;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewrite;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollection;

class ShopDeleteAfter implements ObserverInterface
{
    /**
     * @var UrlRewriteCollection
     */
    private $rewriteCollection;
    /**
     * @var UrlRewrite
     */
    private $urlRewrite;

    /**
     * ShopDeleteAfter constructor.
     * @param UrlRewriteCollection $rewriteCollection
     * @param UrlRewrite $urlRewrite
     */
    public function __construct(
        UrlRewriteCollection $rewriteCollection,
        UrlRewrite $urlRewrite
    ) {
        $this->rewriteCollection = $rewriteCollection;
        $this->urlRewrite = $urlRewrite;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws Exception
     */
    public function execute(Observer $observer)
    {
        $shop = $observer->getEvent()->getObject();

        $collection = $this->rewriteCollection->addFieldToFilter(
            'target_path',
            'storelocator/shop/shop/shop_id/' . $shop->getShopId()
        );
        /** @var \Magento\UrlRewrite\Model\UrlRewrite $rewrite */
        foreach ($collection->getItems() as $rewrite) {
            $this->urlRewrite->delete($rewrite);
        }

    }
}
