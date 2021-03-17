<?php
namespace Elogic\AdminCrud\Controller\Adminhtml\Post;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
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

    public function __construct(Context $context, JsonFactory $jsonFactory, LoggerInterface $logger)
    {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->logger = $logger;
    }

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
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $model_id) {
                    $model = $this->_objectManager->create('Elogic\AdminCrud\Model\Shop')->load($model_id);
                    try {
                        $model->setData(array_merge($model->getData(), $postItems[$model_id]));
                        $model->save();
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
