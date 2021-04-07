<?php
namespace OmniPro\Blog\Block;

class Title extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getTitle(){
        return "My spectacular blog";
    }

    public function getSubTitle(){
        return "A totally false statement";
    }
}
