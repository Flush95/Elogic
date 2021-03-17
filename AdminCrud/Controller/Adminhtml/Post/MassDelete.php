<?php
namespace Elogic\AdminCrud\Controller\Adminhtml\Post;

use Elogic\AdminCrud\Model\ResourceModel\ShopCollections\CollectionFactory;
use Elogic\AdminCrud\Model\Shop;
use Elogic\AdminCrud\Model\ShopRepository;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action
{
    /**
     * @var Filter
     */
    private Filter $filter;
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $collection = null;
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(__('Some error have been occurred when delete shops'));
            return $this->_redirect("admin_crud/index/index");
        }

        if (!is_null($collection)) {
            $collectionSize = $collection->getSize();
            /** @var Shop $shop */
            foreach ($collection as $shop) {
                /** @var ShopRepository $shopRepository */
                $shopRepository = ObjectManager::getInstance()->create('Elogic\AdminCrud\Model\ShopRepository');
                try {
                    $shopRepository->deleteShopById($shop->getShopId());
                } catch (CouldNotDeleteException | NoSuchEntityException $e) {
                    $this->messageManager->addErrorMessage(__('Shop with id=%1 cannot be deleted.'), $shop->getShopId());
                }
            }
            $this->messageManager->addSuccessMessage(__('%1 %2 shop(s) have been deleted.', $collectionSize, $collectionSize > 1 ? 'shop' : 'shops'));
        }
        return $this->_redirect("admin_crud/index/index");
    }
}
