<?php

namespace Elogic\AdminCrud\Block\Adminhtml\Button;

use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Ui\Component\Control\Container;

/**
 * Class Save
 */
class Save extends Generic implements ButtonProviderInterface
{
    /**
     * {@inheritdoc}
     */
    /*public function getButtonData()
    {
        return [
            'label' => __('Custom Button'),
            'class' => 'action-secondary',
            'on_click' => 'alert("Hello World")',
            'sort_order' => 10
        ];
    }*/
    /**
     * @return array
     */
    public function getButtonData(): array
    {

        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => 'elogic_admincrud_form.elogic_admincrud_form',
                                'actionName' => 'save',
                                'params' => [
                                    true,
                                    [
                                        'back' => 'continue'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'class_name' => Container::DEFAULT_CONTROL
        ];
    }


}
