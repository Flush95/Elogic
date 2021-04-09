<?php
namespace Elogic\StoreLocator\Controller\Adminhtml\Post;

use Elogic\StoreLocator\Api\ShopRepositoryInterface;
use Elogic\StoreLocator\Model\ResourceModel\ShopCollections\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action
{
    private const REDIRECT_PATH = 'store_locator/index/index';
    /**
     * @var Filter
     */
    private $filter;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var ShopRepositoryInterface
     */
    private $shopRepository;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param ShopRepositoryInterface $shopRepository
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ShopRepositoryInterface $shopRepository
    ) {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->shopRepository = $shopRepository;
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
            return $this->_redirect(self::REDIRECT_PATH);
        }

        if (!is_null($collection)) {
            $collectionSize = $collection->getSize();

            foreach ($collection as $shop) {
                try {
                    $this->shopRepository->deleteShopById($shop->getShopId());
                } catch (CouldNotDeleteException | NoSuchEntityException $e) {
                    $this->messageManager->addErrorMessage(__('Shop with id=%1 cannot be deleted.'), $shop->getShopId());
                }
            }

            $this->messageManager->addSuccessMessage(__('%1 %2 have been deleted.', $collectionSize, $collectionSize > 1 ? 'shop' : 'shops'));
        }

        return $this->_redirect(self::REDIRECT_PATH);
    }
}
