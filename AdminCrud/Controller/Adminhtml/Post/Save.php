<?php
namespace Elogic\AdminCrud\Controller\Adminhtml\Post;

use Elogic\AdminCrud\Helper\Geo;
use Elogic\AdminCrud\Model\Shop;
use Elogic\AdminCrud\Model\ShopFactory;
use Elogic\AdminCrud\Model\ShopRepository;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem\Driver\File;

class Save extends Action
{

    /**
     * @var Shop
     */
    private Shop $shopModel;
    /**
     * @var File
     */
    private File $file;
    /**
     * @var Geo
     */
    private Geo $geo;
    /**
     * @var ShopFactory
     */
    private ShopFactory $factory;

    /**
     * Create constructor.
     * @param Context $context
     * @param Shop $shopModel
     * @param ShopFactory $factory
     * @param File $file
     * @param Geo $geo
     */
    public function __construct(
        Context $context,
        Shop $shopModel,
        File $file,
        Geo $geo,
        ShopFactory $factory
    ) {
        parent::__construct($context);
        $this->shopModel = $shopModel;
        $this->file = $file;
        $this->geo = $geo;
        $this->factory = $factory;
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();

        $id = isset($data['shop_id']) ? intval($data['shop_id']) : null;
        unset($data['key']);
        unset($data['back']);
        unset($data['form_key']);
        $fileName = $data['img_url'][0]['name'];
        $data['img_url'] = $fileName;

        /** @var ShopRepository $shopRepository */
        $shopRepository = ObjectManager::getInstance()->get(ShopRepository::class);

        $shop = $this->factory->create();
        if ($id != null) {
            try {
                $shop = $shopRepository->getShopById($id);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('Error occurred when creating shop.'));
                return $this->_redirect("admin_crud/index/index");
            }
        }
        $shop->setData($data);

        try {
            $shopRepository->saveShop($shop);
            $this->messageManager->addSuccessMessage(__('Shop have been saved.'));
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('Error occurred when creating shop.'));
        }
        return $this->_redirect("admin_crud/index/index");
    }
}
