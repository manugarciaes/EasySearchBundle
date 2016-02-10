<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Manu Garcia <manugarciaes@gmail.com>
 */

namespace manugarciaes\SearchBundle\Aggregation\Interfaces;

/**
 * Interface AggregationInterface
 * @package SearchBundle\Aggregation\Interfaces
 */
interface AggregationInterface
{
    /**
     * @return mixed
     */
    public function getAggregation();
}
