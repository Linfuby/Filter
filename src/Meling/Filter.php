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
     * @return Filter\Lists\Fields\Actions
     */
    public function actions()
    {
        return $this->builder->actions();
    }

    /**
     * @return Filter\Lists\Fields\Brands
     */
    public function brands()
    {
        return $this->builder->brands();
    }

    /**
     * @return Filter\Lists\Fields\Categories
     */
    public function categories()
    {
        return $this->builder->categories();
    }

    /**
     * @return Filter\Lists\Fields\Cities
     */
    public function cities()
    {
        return $this->builder->cities();
    }

    /**
     * @return Filter\Lists\Fields\Types
     */
    public function extraName()
    {
        return $this->builder->data()->get('extraName');
    }

    /**
     * @return Filter\Lists\Fields\Prices
     */
    public function prices()
    {
        return $this->builder->prices();
    }

    /**
     * @return Filter\Products
     */
    public function products()
    {
        return $this->builder->products();
    }

    /**
     * @return Filter\Lists\Fields\Seasons
     */
    public function seasons()
    {
        return $this->builder->seasons();
    }

    /**
     * @return Filter\Lists\Fields\Sexes
     */
    public function sexes()
    {
        return $this->builder->sexes();
    }

    /**
     * @return Filter\Lists\Fields\Shops
     */
    public function shops()
    {
        return $this->builder->shops();
    }

    /**
     * @return Filter\Lists\Fields\Types
     */
    public function types()
    {
        return $this->builder->types();
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
