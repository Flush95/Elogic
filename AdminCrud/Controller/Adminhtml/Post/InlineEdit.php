<?php
namespace Elogic\AdminCrud\Controller\Adminhtml\Post;

use Elogic\AdminCrud\Model\Shop;
use Elogic\AdminCrud\Model\ShopRepository;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Index
 */
class InlineEdit extends Action
{

    /**
     * @var JsonFactory
     */
    private JsonFactory $jsonFactory;
    private $logger;

    /**
     * InlineEdit constructor.
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param LoggerInterface $logger
     */
    public function __construct(Context $context, JsonFactory $jsonFactory, LoggerInterface $logger)
    {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->logger = $logger;
    }

    /**
     * @return ResponseInterface|Json|ResultInterface
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $this->logger->info(json_encode($this->getRequest()->getParams()));
        $this->logger->debug(json_encode($this->getRequest()->getParams()));

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);

            if (!count($postItems)) {
                $messages[] = __('Please correct data');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $model_id) {

                    /** @var ShopRepository $shopRepository */
                    $shopRepository = $this->_objectManager->create('Elogic\AdminCrud\Model\ShopRepository');

                    try {
                        /** @var Shop $model */
                        $model = $shopRepository->getShopById($model_id);
                        $model->setData(array_merge($model->getData(), $postItems[$model_id]));
                        $shopRepository->saveShop($model);
                    } catch (Exception $e) {
                        $messages[] = "[ID: {$model_id}]  {$e->getMessage()}";
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
