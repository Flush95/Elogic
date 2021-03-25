<?php
namespace Elogic\StoreLocator\Controller\Shop;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Result\PageFactory;

class Shop implements ActionInterface
{
    protected PageFactory $pageFactory;
    private Http $httpRequest;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param Http $httpRequest
     */
    public function __construct(Context $context, PageFactory $pageFactory, Http $httpRequest)
    {
        $this->pageFactory = $pageFactory;
        $this->httpRequest = $httpRequest;
    }

    public function execute()
    {
        $shopId = $this->httpRequest->getParam('shop_id');
        $page = $this->pageFactory->create();
        if ($shopId && is_numeric($shopId)) {
            $shopName = ObjectManager::getInstance()
                ->create('\Elogic\StoreLocator\Api\ShopRepositoryInterface')
                ->getShopById(intval($shopId))
                ->getShopName();
            $page->getConfig()->getTitle()->set(ucfirst($shopName));
        }
        return $page;
    }

}
