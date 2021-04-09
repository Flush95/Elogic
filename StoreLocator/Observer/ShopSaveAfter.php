<?php
declare(strict_types=1);

namespace Elogic\StoreLocator\Observer;

use Elogic\StoreLocator\Model\Shop;
use Exception;
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
    private $rewriteResourceModel;
    /**
     * @var UrlRewriteCollection
     */
    private $rewriteCollection;
    /**
     * @var UrlRewriteModel
     */
    private $rewriteModel;
    /**
     * @var StoreRepositoryInterface
     */
    private $storeRepository;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * ShopSaveAfter constructor.
     * @param UrlRewrite $rewriteResourceModel
     * @param UrlRewriteCollection $rewriteCollection
     * @param UrlRewriteModel $rewriteModel
     * @param StoreRepositoryInterface $storeRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        UrlRewrite $rewriteResourceModel,
        UrlRewriteCollection $rewriteCollection,
        UrlRewriteModel $rewriteModel,
        StoreRepositoryInterface $storeRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->rewriteResourceModel = $rewriteResourceModel;
        $this->rewriteCollection = $rewriteCollection;
        $this->rewriteModel = $rewriteModel;
        $this->storeRepository = $storeRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
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

            $stores = $this->storeRepository->getList();
            foreach ($stores as $store) {
                if (str_contains($store->getName(), $shop->getShopName())) {
                    $this->rewriteModel->setStoreId($store->getId());
                } else {
                    $this->rewriteModel->setStoreId($this->storeManager->getStore()->getStoreId());
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
