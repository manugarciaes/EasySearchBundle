<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Manu Garcia <manugarciaes@gmail.com>
 */

namespace manugarciaes\SearchBundle\Repository;

use Elastica\Aggregation\AbstractAggregation;
use Elastica\Filter\AbstractFilter;
use Elastica\Query;
use FOS\ElasticaBundle\Repository;

/**
 * Class SearchRepository
 * @package SearchBundle\Repository
 */
class SearchRepository extends Repository
{
    /**
     * Elastic use this fields for the search
     * @var array
     */
    protected $searchFields = [];

    protected $filters = [];

    protected $aggregations = [];

    protected $sort = [];

    /**
     * Search for products with specified filters/conditions
     *
     * @param string $search
     * @return \FOS\ElasticaBundle\Paginator\PaginatorAdapterInterface
     */
    public function search($search)
    {
        $query = $this->getFilteredSearchTermQuery($search, []);
        $query = $this->addProductAggregations($query);

        if (!empty($this->sort) && is_array($this->sort)) {
            $query->setSort($this->sort);
        }

        return $this->finder->find($query, 1000);
    }

    /**
     * @param AbstractAggregation $aggregation
     */
    public function addAggregation(AbstractAggregation $aggregation)
    {
        $this->aggregations[] = $aggregation;
    }

    /**
     * Set SearchFields
     *
     * @param array $searchFields
     */
    public function setSearchFields($searchFields)
    {
        $this->searchFields = $searchFields;
    }

    /**
     * @param array $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }
    /**
     * @param AbstractFilter $filter
     */
    public function addFilter(AbstractFilter $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        if (!empty($this->filters)) {
            $boolFilter = new \Elastica\Filter\BoolAnd();

            foreach ($this->filters as $filter) {
                $boolFilter->addFilter($filter);
            }

            return $boolFilter;
        }
    }
    /**
     * Get Filtered search term query
     *
     * @param $search
     *
     * @return Query|Query\AbstractQuery
     */
    protected function getFilteredSearchTermQuery($search)
    {
        $searchQuery = $this->getSearchTermQuery($search);
        $searchFilter = null;

        $searchFilter = $this->getFilters();

        $query = $this->getFinalQuery($searchQuery, $searchFilter);

        return $query;
    }

    /**
     * Get final elastic query
     *
     * @param Query\AbstractQuery $query
     * @param AbstractFilter $filter
     *
     * @return Query|Query\AbstractQuery
     */
    protected function getFinalQuery(Query\AbstractQuery $query = null, AbstractFilter $filter = null)
    {
        $filtered = new \Elastica\Query\Filtered($query, $filter);
        $query = \Elastica\Query::create($filtered);

        return $query;
    }

    /**
     * Get the query for a search term
     *
     * @param string|boolean $search
     * @return Query\Bool
     */
    protected function getSearchTermQuery($search = false)
    {
        $searchFields = [];

        foreach ($this->searchFields as $fieldName) {
            $searchFields[$fieldName] = $search;
        }

        $elasticaQuery = $this->getElasticaQuery($searchFields);

        return $elasticaQuery;
    }

    /**
     * Add aggregations to the query
     *
     * @param Query $query
     *
     * @return Query
     */
    protected function addProductAggregations(Query $query)
    {
        foreach ($this->aggregations as $aggregation) {
            $query->addAggregation($aggregation);
        }

        return $query;
    }
    /**
     * Create bool query with the fields where you want to look for
     * and the value to look for foreach one
     *
     * @param array $searchFields
     *
     * @return \Elastica\Query\Bool
     */
    protected function getElasticaQuery(array $searchFields)
    {
        $query = new \Elastica\Query\BoolQuery();

        foreach ($searchFields as $fieldName => $searchValue) {
            if (is_array($searchValue)) {
                $query = new \Elastica\Query\Terms($fieldName, $searchValue);

            } else {
                $fieldQuery = new \Elastica\Query\MatchPhrase();
                $fieldQuery->setFieldQuery($fieldName, $searchValue);
                $fieldQuery->setFieldParam($fieldName, 'slop', 1500);
                $query->addShould($fieldQuery);
            }

        }

        return $query;
    }
}
