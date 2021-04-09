<?php
namespace Elogic\StoreLocator\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\View\Result\PageFactory;

class Index implements ActionInterface
{
    protected $pageFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;
    /**
     * @var Http
     */
    private $http;
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $url;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param \Magento\Customer\Model\Session $session
     * @param Http $http
     * @param \Magento\Framework\UrlInterface $url
     */
    public function __construct(Context $context, PageFactory $pageFactory, \Magento\Customer\Model\Session $session, Http $http, \Magento\Framework\UrlInterface $url)
    {
        $this->pageFactory = $pageFactory;
        $this->session = $session;
        $this->http = $http;
        $this->url = $url;
    }

    public function execute()
    {
        if (!$this->session->isLoggedIn()) {
            $this->http->setRedirect($this->url->getUrl('customer/account/login'), 301);
        }

        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->set("StoreLocator");
        return $page;
    }

}
