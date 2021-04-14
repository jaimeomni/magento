<?php
namespace OmniPro\BlogNoRequire\Api;

use \OmniPro\BlogNoRequire\Api\Data\BlogInterface;
use \OmniPro\BlogNoRequire\Api\Data\BlogSearchResultInterface;

interface BlogRepositoryInterface
{
    /**
     * @param int $id
     * @return \OmniPro\BlogNoRequire\Api\Data\BlogInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);
 
    /**
     * @param \OmniPro\BlogNoRequire\Api\Data\BlogInterface $blog
     * @return \OmniPro\BlogNoRequire\Api\Data\BlogInterface
     */
    public function save(BlogInterface $blog);
 
    /**
     * @param \OmniPro\BlogNoRequire\Api\Data\BlogInterface $blog
     * @return void
     */
    public function delete(BlogInterface $blog);
 
    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \OmniPro\BlogNoRequire\Api\Data\BlogSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
