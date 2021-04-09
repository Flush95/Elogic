<?php
declare(strict_types=1);
namespace Elogic\StoreLocator\Controller\Adminhtml\Post;

use Elogic\StoreLocator\Api\ShopRepositoryInterface;
use Elogic\StoreLocator\Model\ShopFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action implements HttpGetActionInterface
{

    /**
     * @var ShopFactory
     */
    private $shopFactory;
    /**
     * @var PageFactory
     */
    private $resultPageFactory;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var ShopRepositoryInterface
     */
    private $shopRepository;

    /**
     * Edit constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param ShopFactory $shopFactory
     * @param ShopRepositoryInterface $shopRepository
     * @param Session $session
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ShopFactory $shopFactory,
        ShopRepositoryInterface $shopRepository,
        Session $session
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->shopFactory = $shopFactory;
        $this->session = $session;
        $this->shopRepository = $shopRepository;
    }


    /**
     * @return ResponseInterface|Redirect|ResultInterface|Page
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('shop_id');
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Elogic_AdminCrud::crud');

        if (!empty($id)) {
            $shop = $this->shopRepository->getShopById((int) $id);
            if (!$shop->getShopId()) {
                $this->messageManager->addErrorMessage(__('This shop no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        } else {
            $shop = $this->shopFactory->create();
            $this->session->setModel($shop);

            $resultPage->getConfig()->getTitle()->prepend(__('New Shop'));
            return $resultPage;
        }
        $resultPage->getConfig()->getTitle()->prepend(__('Edit: ') . $shop->getShopName());

        return $resultPage;
    }
}
