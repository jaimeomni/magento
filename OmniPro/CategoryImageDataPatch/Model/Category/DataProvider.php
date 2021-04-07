<?php
namespace OmniPro\CategoryImageDataPatch\Model\Category;

class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{
 
	protected function getFieldsMap()
	{
    	$fields = parent::getFieldsMap();
        $fields['design'][] = 'logo'; // custom image field
    	
    	return $fields;
	}
}