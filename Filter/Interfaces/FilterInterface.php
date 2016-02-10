<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Manu Garcia <manugarciaes@gmail.com>
 */
namespace manugarciaes\SearchBundle\Filter\Interfaces;

/**
 * Interface FilterInterface
 * @package SearchBundle\Filter\Interfaces
 */
interface FilterInterface
{
    /**
     * @return mixed
     */
    public function getFilter();

    /**
     * @param array|string $value
     * @return mixed
     */
    public function setValue($value);

    /**
     * @param string $field
     * @return mixed
     */
    public function setField($field);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return mixed
     */
    public function getField();
}