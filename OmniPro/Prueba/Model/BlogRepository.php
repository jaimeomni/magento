<?php

namespace OmniPro\Prueba\Model;

use Magento\Framework\Api\ImageContent;
use OmniPro\Prueba\Api\Data\BlogInterface;
use OmniPro\Prueba\Api\Data\BlogSearchResultInterface;
use \OmniPro\Prueba\Model\ResourceModel\Blog\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\Filesystem\DirectoryList;

class BlogRepository implements \OmniPro\Prueba\Api\BlogRepositoryInterface
{
    protected $_blogInterfaceFactory;
    protected $_blogCollectionFactory;
    protected $_blogSearchResultFactory;


    

    /**
     * @param \Magento\Framework\Api\Data\ImageContentInterfaceFactory
     */
    private $imageContentInterfaceFactory;

    /**
     * @param \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @param \Magento\Framework\Image\AdapterFactory
     */
    private $adapterFactory;





    /**
     * @param \Magento\Framework\Api\ImageProcessor
     */
    private $imageProcessor;

    /**
     * @param \Magento\Framework\Filesystem
     */
    private $filesystem;

    public function __construct(
        \OmniPro\Prueba\Api\Data\BlogInterfaceFactory $blogInterfaceFactory,
        \OmniPro\Prueba\Model\ResourceModel\Blog\CollectionFactory $blogCollectionFactory,
        \OmniPro\Prueba\Api\Data\BlogSearchResultInterfaceFactory $blogSearchResultInterfaceFactory,
        \Magento\Framework\Api\Data\ImageContentInterfaceFactory $imageContentInterfaceFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        \Magento\Framework\Api\ImageProcessor $imageProcessor,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->_blogInterfaceFactory = $blogInterfaceFactory;
        $this->_blogCollectionFactory = $blogCollectionFactory;
        $this->_blogSearchResultFactory = $blogSearchResultInterfaceFactory;
        $this->imageContentInterfaceFactory = $imageContentInterfaceFactory;
        $this->uploaderFactory = $uploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->imageProcessor = $imageProcessor;
        $this->filesystem = $filesystem;
    }

    public function getById($id)
    {
        $blog = $this->_blogInterfaceFactory->create();
        $blog->getResource()->load($blog, $id);
        if (!$blog->getId()) {
            throw new NoSuchEntityException(__('Unable to load blog with id "%1"', $id));
        }
        return $blog;
    }

    public function save(BlogInterface $blog)
    {
        /* data = explode(',', $blog->getImg());

        $fileContent = @base64_decode($data[1], true);

        $uploader = $this->uploaderFactory->create();
        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);

        $imageAdapter = $this->adapterFactory->create();
        $uploader->addValidateCallback('custom_image_upload', $imageAdapter, 'validateUploadFile');
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(false);

        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $destinationPath = $mediaDirectory->getAbsolutePath('blog/post');
        $result = $uploader->save($destinationPath); */

        /* $imageContent = $this->imageContentInterfaceFactory->create();
        $imageContent->setBase64EncodedData($data[1]);
        $path = $this->imageProcessor->processImageContent('blog/post', $imageContent);
 */
        /*  $fileContent = base64_decode($imageContent->getBase64EncodedData(), true);
        $tmpDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $fileName = $imageContent->getName();
        $tmpFileName = substr(md5(rand()), 0, 7) . '.' . $fileName;
        $tmpDirectory->writeFile($tmpFileName, $fileContent);

        $fileAttributes = [
            'name' => $imageContent->getName(),
            'type' => $imageContent->getType(),
            'tmp_name' => $tmpDirectory->getAbsolutePath() . $tmpFileName
        ];

        $uploader = $this->uploaderFactory->create(['fileId' => $fileContent]);
        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);

        $imageAdapter = $this->adapterFactory->create();
        $uploader->addValidateCallback('custom_image_upload', $imageAdapter, 'validateUploadFile');
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(false);

        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $destinationPath = $mediaDirectory->getAbsolutePath('blog/post');
        $result = $uploader->save($destinationPath);

        $path = $result['file']; */

        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $destinationPath = $mediaDirectory->getAbsolutePath('blog/post/');

        $data = $blog->getImg();
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        $fileName = substr(md5(rand()), 0, 7);
        $finalFileName = $fileName . uniqid() . '.png';
        file_put_contents($destinationPath . $finalFileName, $data);

        $blog->setImg('blog/post/' . $finalFileName);
        //$fileContent = base64_decode($data[1], true);

        $blog->getResource()->save($blog);
        return $blog;
    }

    public function delete(BlogInterface $blog)
    {
        $blog->getResource()->delete($blog);
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->_blogCollectionFactory->create();

        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);

        $collection->load();

        return $this->buildSearchResult($searchCriteria, $collection);
    }

    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $searchResults = $this->_blogSearchResultFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
