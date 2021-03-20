<?php
namespace Elogic\StoreLocator\Ui\Component\Listing\Columns\Column;

use Magento\Catalog\Helper\Image;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Thumbnail extends Column
{
    const ALT_FIELD = 'title';
    const ROUTE_PATH = 'store_locator/mainController/index';
    const DEFAULT_IMG = 'default.png';
    const PUB_MEDIA_PATH = 'pub/media/images/';
    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;
    /**
     * @var Image
     */
    private Image $imageHelper;
    /**
     * @var UrlInterface
     */
    private UrlInterface $urlBuilder;

    /**
     * Thumbnail constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Image $imageHelper
     * @param UrlInterface $urlBuilder
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Image $imageHelper,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->imageHelper = $imageHelper;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     * @throws NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');

            foreach ($dataSource['data']['items'] as & $item) {
                $url = '';
                $fileName = $item[$fieldName];
                if ($fileName != '') {
                    $url = $this->getImgPath() . $fileName;
                    if (!file_exists(self::PUB_MEDIA_PATH . $fileName)) {
                        $url = $this->getImgPath() . 'default/' . self::DEFAULT_IMG;
                    }
                }
                $item[$fieldName . '_src'] = $url;
                $item[$fieldName . '_alt'] = $this->getAlt($item) ?: '';
                /*$item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    'admin_crud/mainController/index',
                    ['shop_id' => $item['shop_id']]
                );*/
                $item[$fieldName . '_orig_src'] = $url;
            }
        }

        return $dataSource;
    }

    protected function getAlt($row)
    {
        $altField = $this->getData('config/img_url') ?: self::ALT_FIELD;
        return isset($row[$altField]) ? $row[$altField] : null;
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    private function getImgPath(): string
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'images/';
    }
}
