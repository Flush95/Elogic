<?php
namespace Elogic\AdminCrud\Controller\Adminhtml\Post;

use Elogic\AdminCrud\Model\ResourceModel\ShopCollections\CollectionFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action
{
    private RequestInterface $request;
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
     * @param RequestInterface $request
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        RequestInterface $request,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        $this->request = $request;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        /*var_dump($this->filter->getCollection($this->collectionFactory->create())->getSize());
        die();*/
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $item) {
            $objectManager = ObjectManager::getInstance();
            $product = $objectManager->create('Elogic\AdminCrud\Model\Shop')->load($item->getId());
            $product->delete();
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 element(s) have been deleted.', $collectionSize));

        return $this->_redirect("admin_crud/maincontroller/index");
    }
}
