<?php

namespace Elogic\StoreLocator\Block\Adminhtml\Button;

use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Ui\Component\Control\Container;

class Delete extends Generic implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Delete'),
            'class' => 'scalable delete',
            'on_click' => 'deleteConfirm(\''
                . __('Are you sure you want to delete this shop?')
                . '\', \'' . $this->getDeleteUrl() . '\')',
            'class_name' => Container::DEFAULT_CONTROL
        ];
    }

    /**
     * @return string
     */
    public function getDeleteUrl(): string
    {
        $vendor_id = $this->context->getRequestParam('shop_id');
        return $this->getUrl('*/*/delete', ['shop_id' => $vendor_id ? $vendor_id : 0]);
    }
}
