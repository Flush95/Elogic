<?php
namespace Elogic\StoreLocator\Controller\Adminhtml\Post;

use Elogic\StoreLocator\Api\ShopRepositoryInterface;
use Elogic\StoreLocator\Api\Data\ShopInterface;
use Elogic\StoreLocator\Model\Shop;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;


class InlineEdit extends Action
{

    /**
     * @var JsonFactory
     */
    private $jsonFactory;
    /**
     * @var ShopRepositoryInterface
     */
    private $shopRepository;

    /**
     * InlineEdit constructor.
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param ShopRepositoryInterface $shopRepository
     */
    public function __construct(Context $context, JsonFactory $jsonFactory, ShopRepositoryInterface $shopRepository)
    {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->shopRepository = $shopRepository;
    }

    /**
     * @return ResponseInterface|Json|ResultInterface
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);

            if (!count($postItems)) {
                $messages[] = __('Please correct data');
                $error = true;
            } else {

                foreach (array_keys($postItems) as $model_id) {

                    try {
                        /** @var Shop $shop */
                        $shop = $this->shopRepository->getShopById($model_id);
                        $shop->setData(array_merge($shop->getData(), $postItems[$model_id]));
                        $this->shopRepository->saveShop($shop);
                        $messages[] = __('Changes in ' . $shop->getShopName() . ' have been saved');
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
