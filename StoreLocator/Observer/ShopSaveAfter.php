<?php
declare(strict_types=1);

namespace Elogic\StoreLocator\Observer;

use Elogic\StoreLocator\Model\Shop;
use Exception;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewrite;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollection;
use Magento\UrlRewrite\Model\UrlRewrite as UrlRewriteModel;

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

        $rewriteCollection = $this->rewriteCollection->addFieldToFilter(
            'target_path',
            'storelocator/shop/shop/shop_id/' . $shop->getShopId()
        );

        if ($rewriteCollection->getSize() == 0) {

            /** @var StoreRepositoryInterface $repository */
            $repository = ObjectManager::getInstance()->create(StoreRepositoryInterface::class);
            $stores = $repository->getList();
            foreach ($stores as $store) {
                if (str_contains($store->getName(), $shop->getShopName())) {
                    $this->rewriteModel->setStoreId($store->getId());
                }
            }

            $this->rewriteModel->setTargetPath('storelocator/shop/shop/shop_id/' . $shop->getShopId());
            $this->rewriteModel->setRequestPath('shops/' . $shop->getUrlKey());

            try {
                $this->rewriteResourceModel->save($this->rewriteModel);
            } catch (Exception $e) {
                var_dump($e);
                die();
            }
        }
    }
}
