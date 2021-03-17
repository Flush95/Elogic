<?php
namespace Elogic\AdminCrud\Controller\Adminhtml\Index;

use Elogic\AdminConfig\Helper\Data;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 */
class Index extends Action
{
    const MENU_ID = 'Elogic_AdminCrud::crud';

    /**
     * @var PageFactory
     */
    protected PageFactory $pageFactory;
    /**
     * @var Data
     */
    private Data $data;

    public function __construct(Context $context, PageFactory $pageFactory, Data $data)
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->data = $data;
    }

    public function execute()
    {
        if (!$this->moduleEnabled()) {
            $this->_forward('path/to/beautiful_life');
        }

        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu(static::MENU_ID);

        return $resultPage;
    }

    protected function moduleEnabled(): bool
    {
        return boolval($this->data->getModuleStatus());
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magento_Backend::admin');
    }
}
