<?php
namespace OmniPro\BlogNoRequire\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface BlogSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \OmniPro\BlogNoRequire\Api\Data\BlogInterface[]
     */
    public function getItems();
 
    /**
     * @param \OmniPro\BlogNoRequire\Api\Data\BlogInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
