<?php
declare(strict_types=1);
namespace Elogic\StoreLocator\Controller\Adminhtml\Post;

use Elogic\StoreLocator\Api\ShopRepositoryInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;

class Delete extends Action implements HttpGetActionInterface
{
    /**
     * @var ShopRepositoryInterface
     */
    private $shopRepository;

    /**
     * Delete constructor.
     * @param Context $context
     * @param ShopRepositoryInterface $shopRepository
     */
    public function __construct(Context $context, ShopRepositoryInterface $shopRepository)
    {
        parent::__construct($context);
        $this->shopRepository = $shopRepository;
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('shop_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $model = $this->shopRepository;
            try {
                $model->deleteShopById(intval($id));
                $this->messageManager->addSuccessMessage(__('You deleted shop.'));
                return $resultRedirect->setPath('store_locator/index/index/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath(
                    'store_locator/post/edit',
                    ['shop_id' => $this->getRequest()->getParam('shop_id')]
                );
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find an shop to delete.'));
        return $resultRedirect->setPath('store_locator/index/index/');
    }
}
