<?php
namespace Elogic\StoreLocator\Controller\Index;

use Elogic\AdminCrud\Model\ShopRepository;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

class Index implements ActionInterface
{
    protected PageFactory $pageFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(Context $context, PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
    }

    public function execute()
    {
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->set("StoreLocator");
        return $page;
    }

    public function getShopGeo(int $id)
    {
        $objectManager = ObjectManager::getInstance();

        /** @var ShopRepository $product */
        $product = $objectManager->create('Elogic\AdminCrud\Model\ShopRepository');
        $shop = null;
        try {
            $shop = $product->getShopById($id);
        } catch (NoSuchEntityException | CouldNotDeleteException $e) {
            echo $e->getMessage();
        }
        return ['latitude' => $shop->getLatitude(), 'longitude' => $shop->getLongitude()];
    }
}
