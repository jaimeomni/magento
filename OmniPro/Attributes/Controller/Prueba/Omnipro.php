<?php
namespace OmniPro\Attributes\Controller\Prueba;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Result\PageFactory;

class Omnipro implements HttpGetActionInterface
{
    private PageFactory $pageFactory;

    
    public function __construct(
        PageFactory $pageFactory
    )
    {
        $this->pageFactory = $pageFactory;
    }
    /**
     * View page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        return $this->pageFactory->create();
    }
}
