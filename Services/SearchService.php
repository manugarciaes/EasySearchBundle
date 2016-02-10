<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Manu Garcia <manugarciaes@gmail.com>
 */

namespace manugarciaes\SearchBundle\Services;

use manugarciaes\SearchBundle\Aggregation\Interfaces\AggregationInterface;
use manugarciaes\SearchBundle\Filter\Interfaces\FilterInterface;
use FOS\ElasticaBundle\Doctrine\RepositoryManager;

/**
 * Class SearchService
 * @package SearchBundle\Services
 */
class SearchService
{
    private $repositoryManager;
    private $searchFilters;
    private $searchRepository;

    /**
     * @param RepositoryManager $repositoryManager
     * @param string            $repositoryName
     * @param array             $searchFields
     */
    public function __construct(
        RepositoryManager $repositoryManager,
        $repositoryName,
        $searchFields
    ) {
        $this->repositoryManager = $repositoryManager;

        $this->searchRepository = $repositoryManager->getRepository($repositoryName);
        $this->searchRepository->setSearchFields($searchFields);

    }

    /**
     * Elastic Search
     * @param string $string
     * @return mixed
     */
    public function search($string)
    {
        return $this->searchRepository->search($string);
    }
    /**
     * @param array $sort
     */
    public function setSort($sort)
    {
        $this->searchRepository->setSort($sort);
    }
    /**
     * @param string $filter
     */
    public function addFilter($filter)
    {
        if ($filter instanceof FilterInterface
            && $filter->getFilter() != false ) {
            $this->searchRepository->addFilter(
                $filter->getFilter()
            );
        }
    }

    /**
     * @param string $aggregation
     */
    public function addAggregation($aggregation)
    {
        if ($aggregation instanceof AggregationInterface) {
            $this->searchRepository->addAggregation(
                $aggregation->getAggregation()
            );
        }
    }
}
