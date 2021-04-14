<?php
namespace OmniPro\BlogNoRequire\Model\ResourceModel;

class Blog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('omnipro_blogn', 'id');
    }
}
