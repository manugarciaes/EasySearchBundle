<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Manu Garcia <manugarciaes@gmail.com>
 */

namespace manugarciaes\SearchBundle\Aggregation;

use manugarciaes\SearchBundle\Aggregation\Interfaces\AggregationInterface;

/**
 * Class BrandAggregation
 * @package SearchBundle\Aggregation
 */
class ObjectAggregation implements AggregationInterface
{
    protected $name;
    protected $field;
    protected $size;

    public function __construct($name, $field, $size = 0)
    {
        $this->name = $name;
        $this->field = $field;
        $this->size = $size;
    }
    /**
     * @return \Elastica\Aggregation\Terms
     */
    public function getAggregation()
    {
        $aggregation = new \Elastica\Aggregation\Terms($this->name);
        $aggregation->setField($this->field);
        $aggregation->setSize($this->size);

        return $aggregation;
    }
}
