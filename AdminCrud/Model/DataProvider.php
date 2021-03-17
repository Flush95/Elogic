<?php
namespace Elogic\AdminCrud\Model;

use Elogic\AdminCrud\Model\ResourceModel\ShopCollections\CollectionFactory;
use Magento\Catalog\Model\Session;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    private const DEFAULT_IMG = 'default/default.png';

    /**
     * @var array
     */
    protected array $_loadedData;
    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @param  $name
     * @param  $primaryFieldName
     * @param  $requestFieldName
     * @param CollectionFactory $storeCollectionFactory
     * @param Filesystem $filesystem
     * @param StoreManagerInterface $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $storeCollectionFactory,
        Filesystem $filesystem,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $storeCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->filesystem = $filesystem;
        $this->storeManager = $storeManager;
    }


    /**
     * @return array
     * @throws NoSuchEntityException
     */
    public function getData(): array
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        if ($shops = $this->collection->getItems()) {
            foreach ($shops as $shop) {

                /** @var Shop $shop */
                $this->_loadedData[$shop->getShopId()] = $shop->getData();

                $imageName = $shop->getImgUrl();

                if (!$this->imageExists($imageName)) {
                    $imageName = self::DEFAULT_IMG;
                }
                $img = [
                    'name' => $imageName,
                    'url' => $this->getMediaUrl() . $imageName,
                    'size' => $this->getImageSize($imageName)
                ];

                $shop['img_url'] = [$img];
                $this->_loadedData[$shop->getShopId()] = $shop->getData();

            }
            return $this->_loadedData;
        }
        $objectManager = ObjectManager::getInstance();
        /** @var Session $session */
        $session = $objectManager->create('\Magento\Catalog\Model\Session');
        if (!$model = $session->getModel()) {
            $emptyShop = $this->collection->getNewEmptyItem();
            $emptyShop->setData($model);
            $this->_loadedData[$emptyShop->getShopId()] = $emptyShop->getData();
            $session->unsModel();
            return $this->_loadedData;
        }
        return [];
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    private function getMediaUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'images/';
    }

    /**
     * @param string $imageName
     * @return int|null
     */
    private function getImageSize(string $imageName): ?int
    {
        return $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->stat('images/' . $imageName)[7];
    }

    /**
     * @param string $imageName
     * @return bool
     */
    public function imageExists(string $imageName): bool
    {
        return $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->isExist('images/' . $imageName);
    }
}
