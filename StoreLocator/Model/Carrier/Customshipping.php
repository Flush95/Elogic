<?php

namespace Elogic\StoreLocator\Model\Carrier;

use Elogic\StoreLocator\Api\ShopRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;

/**
 * Custom shipping model
 */
class Customshipping extends AbstractCarrier implements CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'customshipping';

    /**
     * @var bool
     */
    protected $_isFixed = true;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    private $rateResultFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    private $rateMethodFactory;
    /**
     * @var ProductRepositoryInterface
     */
    private $productLoader;

    private $shopsIds = [];
    /**
     * @var ShopRepositoryInterface
     */
    private $shopRepository;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param ProductRepositoryInterface $productLoader
     * @param ShopRepositoryInterface $shopRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        ProductRepositoryInterface $productLoader,
        ShopRepositoryInterface $shopRepository,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->productLoader = $productLoader;
        $this->shopRepository = $shopRepository;
    }

    /**
     * Custom Shipping Rates Collector
     *
     * @param RateRequest $request
     * @return \Magento\Shipping\Model\Rate\Result|bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->rateResultFactory->create();

        $checkoutProducts = $request->getAllItems();
        $shopsIds = $this->getShopIds($checkoutProducts);
        $availableIds = $this->checkAvailablePickupStores($shopsIds, $checkoutProducts);

        foreach ($this->loadShopNames($availableIds) as $shopName) {
            $method = $this->getMethod($shopName);
            $result->append($method);
        }
        return $result;
    }

    /**
     * @param $item
     * @return \Magento\Quote\Model\Quote\Address\RateResult\Method
     */
    public function getMethod($shopName): \Magento\Quote\Model\Quote\Address\RateResult\Method
    {
        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->rateMethodFactory->create();
        $method->setCarrier($this->_code);
        $method->setCarrierTitle($shopName);

        $method->setMethod($shopName);
        $method->setMethodTitle($this->getConfigData('title'));

        return $method;
    }

    /**
     * @return array
     */
    public function getAllowedMethods(): array
    {
        return [$this->_code => $this->getConfigData('name')];
    }

    /**
     * @param array $allItems
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getShopIds(array $allItems): array
    {
        $shopIds = [];
        foreach ($allItems as $item) {
            $_product = $item->getProduct();
            $pid = $_product->getId();
            $product = $this->productLoader->getById($pid);
            if ($product->getCustomAttribute('product_shops')) {
                $shopIds = array_merge($shopIds, mb_split(',', $product->getCustomAttribute('product_shops')->getValue()));
            }
        }
        return $shopIds;
    }

    /**
     * @param $shopIds
     * @param $allProducts
     * @return array
     */
    public function checkAvailablePickupStores($shopIds, $allProducts): array
    {
        $availableIn = [];
        $count = array_count_values($shopIds);

        foreach ($count as $key => $value) {
            if ($value == count($allProducts)) {
                $availableIn[] = $key;
            }
        }
        return $availableIn;
    }

    /**
     * @param array $shopIds
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function loadShopNames(array $shopIds): array
    {
        $shopNames = [];
        foreach ($shopIds as $id) {
            $shop = $this->shopRepository->getShopById(intval($id));
            $shopNames[] = $shop->getShopName();
        }
        return $shopNames;
    }
}
