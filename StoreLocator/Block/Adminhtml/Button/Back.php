<?php

namespace Elogic\StoreLocator\Block\Adminhtml\Button;

use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Ui\Component\Control\Container;

class Back extends Generic implements ButtonProviderInterface
{

    /**
     * Return to previous page
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Back'),
            'class' => 'back',
            'on_click' => 'Javascript:history.back();',
            'class_name' => Container::DEFAULT_CONTROL
        ];
    }
}
