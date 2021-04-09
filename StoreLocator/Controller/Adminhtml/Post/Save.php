<?php
namespace Elogic\StoreLocator\Controller\Adminhtml\Post;

use Elogic\StoreLocator\Api\ShopProductsRepositoryInterface;
use Elogic\StoreLocator\Api\ShopRepositoryInterface;
use Elogic\StoreLocator\Helper\Geo;
use Elogic\StoreLocator\Model\ResourceModel\ShopCollections\ShopProductsCollectionFactory;
use Elogic\StoreLocator\Model\ShopFactory;
use Elogic\StoreLocator\Model\ShopProducts;
use Elogic\StoreLocator\Model\ShopProductsFactory;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    private const REDIRECT_PATH = 'store_locator/index/index';

    /**
     * @var Geo
     */
    private $geo;
    /**
     * @var ShopFactory
     */
    private $shopFactory;
    /**
     * @var ShopProductsFactory
     */
    private $productsFactory;
    /**
     * @var ShopProductsRepositoryInterface
     */
    private $productsRepository;
    /**
     * @var ShopProductsCollectionFactory
     */
    private $productsCollectionFactory;
    /**
     * @var ShopRepositoryInterface
     */
    private $shopRepository;

    /**
     * Create constructor.
     * @param Context $context
     * @param Geo $geo
     * @param ShopFactory $shopFactory
     * @param ShopProductsFactory $productsFactory
     * @param ShopRepositoryInterface $shopRepository
     * @param ShopProductsRepositoryInterface $productsRepository
     * @param ShopProductsCollectionFactory $productsCollectionFactory
     */
    public function __construct(
        Context $context,
        Geo $geo,
        ShopFactory $shopFactory,
        ShopProductsFactory $productsFactory,
        ShopRepositoryInterface $shopRepository,
        ShopProductsRepositoryInterface $productsRepository,
        ShopProductsCollectionFactory $productsCollectionFactory
    ) {
        parent::__construct($context);
        $this->geo = $geo;
        $this->shopFactory = $shopFactory;
        $this->productsFactory = $productsFactory;
        $this->productsRepository = $productsRepository;
        $this->productsCollectionFactory = $productsCollectionFactory;
        $this->shopRepository = $shopRepository;
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();

        $id = isset($data['shop_id']) ? intval($data['shop_id']) : null;
        $shopProducts = $data['shop_products'];

        unset($data['key']);
        unset($data['back']);
        unset($data['form_key']);
        $fileName = $data['img_url'][0]['name'];
        $data['img_url'] = $fileName;


        $shop = $this->shopFactory->create();
        if ($id != null) {
            try {
                $shop = $this->shopRepository->getShopById($id);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('Error occurred when creating shop.'));
                return $this->_redirect(self::REDIRECT_PATH);
            }
        }

        $shop->setData($data);

        if (is_null($shop->getUrlKey())) {
            $urlKey = preg_replace('#[^0-9a-zа-я]+#iu', '-', $shop->getShopName());
            $urlKey = strtolower($urlKey);
            $urlKey = trim($urlKey, '-');
            $shop->setUrlKey($urlKey);
        }

        try {
            $this->shopRepository->saveShop($shop);

            // Assign product to shop if it is not present
            if (isset($shopProducts) && is_array($shopProducts)) {
                $productsCollection = $this->productsCollectionFactory->create();
                $productsCollection->addFieldToFilter('shop_id', $shop->getShopId())->load();

                /** @var ShopProducts $product */
                foreach ($shopProducts as $productId) {
                    if (is_null($productsCollection->getItemByColumnValue('product_id', $productId))) {
                        $product = $this->productsFactory->create();
                        $product->setTableId(null);
                        $product->setShopId($shop->getShopId());
                        $product->setProductId($productId);
                        $this->productsRepository->saveShopProducts($product);
                    }
                }
            }

            $this->messageManager->addSuccessMessage(__('Shop %1 have been saved.', $shop->getShopName()));
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('Error occurred when creating shop.'));
        }
        return $this->_redirect(self::REDIRECT_PATH);
    }
}
