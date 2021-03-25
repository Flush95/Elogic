<?php
declare(strict_types=1);

namespace Elogic\StoreLocator\Observer;

use Elogic\StoreLocator\Model\Shop;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewrite;
use Magento\UrlRewrite\Model\UrlRewrite as UrlRewriteModel;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollection;

class ShopSaveAfter implements ObserverInterface
{

    /**
     * @var UrlRewrite
     */
    private UrlRewrite $rewriteResourceModel;
    /**
     * @var UrlRewriteCollection
     */
    private UrlRewriteCollection $rewriteCollection;
    /**
     * @var UrlRewriteModel
     */
    private UrlRewriteModel $rewriteModel;

    /**
     * ShopSaveAfter constructor.
     * @param UrlRewrite $rewriteResourceModel
     * @param UrlRewriteCollection $rewriteCollection
     * @param UrlRewriteModel $rewriteModel
     */
    public function __construct(
        UrlRewrite $rewriteResourceModel,
        UrlRewriteCollection $rewriteCollection,
        UrlRewriteModel $rewriteModel
    ) {
        $this->rewriteResourceModel = $rewriteResourceModel;
        $this->rewriteCollection = $rewriteCollection;
        $this->rewriteModel = $rewriteModel;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Shop $shop */
        $shop = $observer->getEvent()->getObject();

        if (is_null($shop->getUrlKey())) {
            $urlKey = preg_replace('#[^0-9a-z]+#i', '-', $shop->getShopName());
            $urlKey = strtolower($urlKey);
            $urlKey = trim($urlKey, '-');
            $shop->setUrlKey($urlKey);
        }

        $rewriteCollection = $this->rewriteCollection->addFieldToFilter(
            'target_path',
            'storelocator/shop/shop/shop_id/' . $shop->getShopId()
        );

        if ($rewriteCollection->getSize() == 0) {
            $this->rewriteModel->setStoreId(1);
            $this->rewriteModel->setTargetPath('storelocator/shop/shop/shop_id/' . $shop->getShopId());
            $this->rewriteModel->setRequestPath('shops/' . $shop->getUrlKey());

            try {
                $this->rewriteResourceModel->save($this->rewriteModel);
            } catch (\Exception $e) {
                var_dump($e);
                die();
            }
        }
    }
}
