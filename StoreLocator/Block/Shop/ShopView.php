<?php

declare(strict_types=1);

namespace Elogic\StoreLocator\Block\Shop;

use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Element\Template;

class ShopView extends Template
{
    /**
     * @var Http
     */
    private Http $httpRequest;

    /**
     * ShopView constructor.
     * @param Http $httpRequest
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(Http $httpRequest, Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->httpRequest = $httpRequest;
    }
}
