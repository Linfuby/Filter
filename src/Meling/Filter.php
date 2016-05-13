<?php
namespace Meling;

/**
 * Компонент Фильтра
 * Class Filter
 * @package Meling
 */
class Filter
{
    /**
     * @var Filter\Builder
     */
    protected $builder;

    /**
     * @param \PHPixie\Database\Connection           $connection
     * @param \PHPixie\Slice\Type\ArrayData\Editable $data
     */
    public function __construct($connection, $data)
    {
        $this->builder = $this->buildBuilder($connection, $data);
    }

    /**
     * @param \PHPixie\Database\Connection           $connection
     * @param \PHPixie\Slice\Type\ArrayData\Editable $data
     * @return Filter\Builder
     */
    protected function buildBuilder($connection, $data)
    {
        return new Filter\Builder($connection, $data);
    }

}
