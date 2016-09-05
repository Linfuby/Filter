<?php
namespace Meling\Filter\Sexes\Categories\ProductTypes;

/**
 * Class ProductType
 * @package Meling\Filter\Sexes\Sex\Categories\Category\ProductTypes
 * @since   2.0
 */
class ProductType
{
    /** @var \Meling\Filter */
    protected $filter;

    /** @var \PHPixie\Slice\Type\ArrayData\Slice */
    protected $data;

    /** @var \Meling\Filter\Sexes\Categories\Category */
    protected $category;

    /** @var mixed */
    protected $id;

    /** @var string */
    protected $name;

    /** @var bool */
    protected $isSelect;

    /** @var Sizes */
    private $sizes;

    /** @var Girths */
    private $girths;

    /** @var Colors */
    private $colors;

    /** @var Attributes */
    private $attributes;

    /**
     * ProductType constructor.
     * @param \Meling\Filter                             $filter
     * @param \PHPixie\Slice\Type\ArrayData\Slice        $data
     * @param \Meling\Filter\Sexes\Categories\Category $category
     * @param mixed                                      $id
     * @param string                                     $name
     * @param bool                                       $isSelect
     * @since 2.0
     */
    public function __construct(\Meling\Filter $filter, \PHPixie\Slice\Type\ArrayData\Slice $data, \Meling\Filter\Sexes\Categories\Category $category, $id, $name, $isSelect = false)
    {
        $this->filter   = $filter;
        $this->data     = $data;
        $this->category = $category;
        $this->id       = $id;
        $this->name     = $name;
        $this->isSelect = $isSelect;
    }

    /**
     * @return Attributes
     * @since 2.0
     */
    public function attributes()
    {
        if($this->attributes === null) {
            $this->attributes = new Attributes($this->filter, $this->data->arraySlice('attributes'), $this);
        }

        return $this->attributes;
    }

    /**
     * @return \Meling\Filter\Sexes\Categories\Category
     * @since 2.0
     */
    public function category()
    {
        return $this->category;
    }

    /**
     * @return Colors
     * @since 2.0
     */
    public function colors()
    {
        if($this->colors === null) {
            $this->colors = new Colors($this->filter, $this->data->arraySlice('colors'), $this);
        }

        return $this->colors;
    }

    /**
     * @return Girths
     * @since 2.0
     */
    public function girths()
    {
        if($this->girths === null) {
            $this->girths = new Girths($this->filter, $this->data->arraySlice('girths'), $this);
        }

        return $this->girths;
    }

    /**
     * @return mixed
     * @since 2.0
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return bool
     * @since 2.0
     */
    public function isSelect()
    {
        return $this->isSelect;
    }

    /**
     * @return string
     * @since 2.0
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return Sizes
     * @since 2.0
     */
    public function sizes()
    {
        if($this->sizes === null) {
            $this->sizes = new Sizes($this->filter, $this->data->arraySlice('sizes'), $this);
        }

        return $this->sizes;
    }

}

