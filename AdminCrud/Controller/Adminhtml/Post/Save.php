<?php
namespace Elogic\AdminCrud\Controller\Adminhtml\Post;

use Elogic\AdminCrud\Helper\Geo;
use Elogic\AdminCrud\Model\ResourceModel\ShopResource;
use Elogic\AdminCrud\Model\Shop;
use Elogic\AdminCrud\Model\ShopFactory;
use Elogic\AdminCrud\Model\ShopRepository;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Filesystem\Driver\File;

class Save extends Action
{

    /**
     * @var ShopResource
     */
    private ShopResource $resourceModel;
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
     * @param ShopResource $resourceModel
     * @param Shop $shopModel
     * @param ShopFactory $factory
     * @param File $file
     * @param Geo $geo
     */
    public function __construct(
        Context $context,
        ShopResource $resourceModel,
        Shop $shopModel,
        File $file,
        Geo $geo,
        ShopFactory $factory
    ) {
        parent::__construct($context);
        $this->resourceModel = $resourceModel;
        $this->shopModel = $shopModel;
        $this->file = $file;
        $this->geo = $geo;
        $this->factory = $factory;
    }

    public function execute()
    {
        $data = $this->getRequest()->getParams();

        $id = isset($data['shop_id']) ? intval($data['shop_id']) : null;
        unset($data['key']);
        unset($data['back']);
        unset($data['form_key']);
        $fileName = $data['img_url'][0]['name'];
        $data['img_url'] = $fileName;

        $shopRepository = ObjectManager::getInstance()->get(ShopRepository::class);

        $shop = $this->factory->create();
        if ($id != null) {
            $shop = $shopRepository->getShopById($id);
        }
        $shop->setData($data);

        try {
            $this->resourceModel->save($shop);
            $this->messageManager->addSuccessMessage(__('Shop have been saved.'));
        } catch (AlreadyExistsException | Exception $e) {
            $this->messageManager->addErrorMessage(__('Error occurred when creating shop.'));
        }
        return $this->_redirect("admin_crud/index/index");
    }
}
