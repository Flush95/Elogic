<?php
namespace Elogic\AdminCrud\Ui\Component\Listing\Columns\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class ShopActions extends Column
{
    protected UrlInterface $urlBuilder;

    public function __construct(ContextInterface $context, UiComponentFactory $uiComponentFactory, UrlInterface $urlBuilder, array $components=[], array $data=[])
    {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {

        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->urlBuilder->getUrl('admin_crud/post/edit', ['shop_id' => $item['shop_id']]),
                    'label' => __('Edit'),
                    'hidden' => false
                ];

                /*$item[$this->getData('name')]['delete'] = [
                    'href' => $this->urlBuilder->getUrl('jeff_contacts/index/delete', ['id' => $item['jeff_contacts_contact_id']]),
                    'label' => __('Delete'),
                    'hidden' => false
                ];*/
            }
        }

        return $dataSource;
    }
}
