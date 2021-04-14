<?php
namespace OmniPro\BlogNoRequire\ViewModel;

class BlogViewModel implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @param \OmniPro\BlogNoRequire\Api\BlogRepositoryInterface
     */
    private $blogRepository;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaBuilder
     */
    //private $searchCriteriaBuilder;
    

    /**
     * @param \Magento\Framework\Api\SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;

    public function __construct(
        \OmniPro\BlogNoRequire\Api\BlogRepositoryInterface $blogRepository,
        //\Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder,
        \Magento\Framework\Api\SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
    
    )
    {
        $this->blogRepository = $blogRepository;
        //$this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        
    }

    public function getPosts() {
        /* $searchCriteria = $this->searchCriteriaBuilder->create();
        $posts = $this->blogRepository->getList($searchCriteria)->getItems();
        return $posts; */
        $sortOrder = $this->sortOrderBuilder->create();
        $sortOrder->setField('created_datetime')->setDirection('DESC');

        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        $searchCriteria = $searchCriteriaBuilder->setSortOrders([$sortOrder])->create();
        
        return $this->blogRepository->getList($searchCriteria)->getItems();
    }

    public function getText() {
        return 'Hola View Model';
    }
}
