<?php
namespace OmniPro\Blog\Block;

class Elements extends \Magento\Framework\View\Element\Template
{

    protected $_collection;


    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \OmniPro\Blog\Model\ResourceModel\Post\Collection $collection,
        array $data = []
    ) {
        $this->_collection=$collection;
        parent::__construct($context, $data);
    }

    public function getPost(){
        return $this->_collection;
    }

    public function orderPostByDate(){
        return $this->_collection->setOrder('created_at', 'DESC');
    }
}
