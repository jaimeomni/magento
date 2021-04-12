<?php

namespace OmniPro\Prueba\Controller\Bootcamp;

use \Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;


class Save extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;

    protected $_blogInterfaceFactory;

    protected $_blogRepository;



    /**
     * @param \Magento\Framework\Filesystem
     */
    private $filesystem;



    /**
     * @param \Magento\MediaStorage\Model\File\UploaderFactory
     */
    private $uploaderFactory;



    /**
     * @param \Magento\Framework\Image\AdapterFactory
     */
    private $adapterFactory;



    /**
     * @param \Magento\User\Model\ResourceModel\User\CollectionFactory
     */
    private $userCollectionFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \OmniPro\Prueba\Api\Data\BlogInterfaceFactory $blogInterfaceFactory,
        \OmniPro\Prueba\Api\BlogRepositoryInterface $blogRepository,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\Image\AdapterFactory $adapterFactory,
        \Magento\User\Model\ResourceModel\User\CollectionFactory $userCollectionFactory
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_blogRepository = $blogRepository;
        $this->filesystem = $filesystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->userCollectionFactory = $userCollectionFactory;
        $this->_blogInterfaceFactory = $blogInterfaceFactory;
        return parent::__construct($context);
    }
    /**
     * View page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        $params = $this->_request->getParams();


        if (!empty($_POST)) {
            # code...
            try {
                //code...
                if (in_array($params['email'], $this->getAdminEmail())) {
                    # code...
                    $path = '';
                    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                        # code...
                        $uploader = $this->uploaderFactory->create(['fileId' => 'image']);
                        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);

                        $imageAdapter = $this->adapterFactory->create();
                        $uploader->addValidateCallback('custom_image_upload', $imageAdapter, 'validateUploadFile');
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(false);

                        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
                        $destinationPath = $mediaDirectory->getAbsolutePath('blog/post');
                        $result = $uploader->save($destinationPath);

                        

                        if ($result) {
                            $path = $result['file'];
                        } else {
                            throw new LocalizedException(__('File cannot to path: $1', $destinationPath));
                        }
                    }else{
                        $this->messageManager->addErrorMessage("not image");
                    }

                    $blog = $this->_blogInterfaceFactory->create();
                    $blog->setTitle($params['titulo'] ?? '');
                    $blog->setEmail($params['email'] ?? '');
                    $blog->setContent($params['contenido'] ?? '');
                    $path != '' ? $blog->setImg('blog/post/' . $path) : $blog->setImg(null);
                    //$blog->setImg($params['img'] ?? '');
                    $this->_blogRepository->save($blog);
                    $this->messageManager->addSuccessMessage('Post saved');
                } else {
                    $this->messageManager->addErrorMessage('Not admin user');
                }
            } catch (\Throwable $th) {
                $this->messageManager->addErrorMessage($th->getMessage());
            } finally{
                return $this->_redirect('*/*/prueba');
            }
        }


        //$params = $this->_request->getParams();
        /**
         * @var \OmniPro\Prueba\Model\Blog $blog
         */
        //$blog = $this->_blogInterfaceFactory->create();
        //$blog->setTitle($params['titulo'] ?? '');
        //$blog->setEmail($params['email'] ?? '');
        //$blog->setContent($params['contenido'] ?? '');
        //$blog->setImg($params['img'] ?? '');
        //$this->_blogRepository->save($blog);
        /**
         * @var \Magento\Framework\Controller\Result\Json $result
         */

        //return $this->_redirect('*/*/prueba');
    }

    public function getAdminEmail()
    {
        $adminUsers = array();
        foreach ($this->userCollectionFactory->create() as $user) :
            if ($user->getRole()->getRoleName() == 'Administrators') {
                array_push($adminUsers, $user->getEmail());
            }
        endforeach;
        return $adminUsers;
    }
}
