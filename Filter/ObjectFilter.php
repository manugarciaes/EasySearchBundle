<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Manu Garcia <manugarciaes@gmail.com>
 */

namespace manugarciaes\SearchBundle\Filter;

use manugarciaes\SearchBundle\Filter\Interfaces\FilterInterface;

/**
 * Generic Class for to do filters using symfony2 services
 * Class ObjectFilter
 * @package SearchBundle\Filter
 */
class ObjectFilter implements FilterInterface
{

    protected $value;
    protected $field;

    /**
     * generic method for to do a filter
     * @return bool|\Elastica\Filter\Bool
     */
    public function getFilter()
    {
        if (!empty($this->getValue())) {
            $filter = new \Elastica\Filter\Bool();

            $filter->addMust(
                new \Elastica\Filter\Terms($this->getField(), [$this->getValue()])
            );

            return $filter;
        }

        return false;
    }
    /**
     * @param array|string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $field
     * @return $this
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }
}
