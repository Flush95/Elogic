<?php
namespace Elogic\StoreLocator\Controller\Shop;

use Elogic\StoreLocator\Api\ShopRepositoryInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Result\PageFactory;

class Shop implements ActionInterface
{
    protected $pageFactory;
    private $httpRequest;
    /**
     * @var ShopRepositoryInterface
     */
    private $shopRepository;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param Http $httpRequest
     * @param ShopRepositoryInterface $shopRepository
     */
    public function __construct(Context $context, PageFactory $pageFactory, Http $httpRequest, ShopRepositoryInterface $shopRepository)
    {
        $this->pageFactory = $pageFactory;
        $this->httpRequest = $httpRequest;
        $this->shopRepository = $shopRepository;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $shopId = $this->httpRequest->getParam('shop_id');
        $page = $this->pageFactory->create();
        if ($shopId && is_numeric($shopId)) {
            $shopName = $this->shopRepository->getShopById(intval($shopId))->getShopName();
            $page->getConfig()->getTitle()->set(ucfirst($shopName));
        }
        return $page;
    }

}
