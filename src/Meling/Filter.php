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
     * @param \PHPixie\Database\Connection           $db
     * @param \PHPixie\Slice\Type\ArrayData\Editable $data
     */
    public function __construct($db, $data)
    {
        $this->builder = $this->buildBuilder($db, $data);
    }

    /**
     * @param \PHPixie\Database\Connection           $db
     * @param \PHPixie\Slice\Type\ArrayData\Editable $data
     * @return Filter\Builder
     */
    protected function buildBuilder($db, $data)
    {
        return new Filter\Builder($db, $data);
    }

}
