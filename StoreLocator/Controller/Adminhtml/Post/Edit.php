<?php
declare(strict_types=1);
namespace Elogic\StoreLocator\Controller\Adminhtml\Post;

use Elogic\StoreLocator\Model\ShopFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action implements HttpGetActionInterface
{

    /**
     * @var ShopFactory
     */
    private ShopFactory $shopFactory;
    /**
     * @var PageFactory
     */
    private PageFactory $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ShopFactory $shopFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->shopFactory = $shopFactory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface|Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('shop_id');
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Elogic_AdminCrud::crud');

        if (!empty($id)) {
            $model =  $this->_objectManager->create('Elogic\StoreLocator\Model\ShopRepository')->getShopById((int) $id);

            if (!$model->getShopId()) {
                $this->messageManager->addErrorMessage(__('This shop no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        } else {
            $model = $this->shopFactory->create();

            $objectManager = ObjectManager::getInstance();
            /** @var Session $session */
            $session = $objectManager->create('\Magento\Catalog\Model\Session');
            $session->setModel($model);

            $resultPage->getConfig()->getTitle()->prepend(__('New Shop'));
            return $resultPage;
        }
        $resultPage->getConfig()->getTitle()->prepend(__('Edit: ') . $model->getShopName());

        return $resultPage;
    }
}
