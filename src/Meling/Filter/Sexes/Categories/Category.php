<?php
namespace Meling\Filter\Sexes\Categories;

/**
 * Class Category
 * @package Meling\Filter\Sexes\Sex\Categories
 * @since   2.0
 */
class Category
{
    /** @var \PHPixie\Database\Connection */
    protected $filter;

    /** @var \Meling\Filter\Sexes\Sex */
    protected $sex;

    /** @var \PHPixie\Slice\Type\ArrayData\Slice */
    protected $data;

    /** @var mixed */
    protected $id;

    /** @var string */
    protected $name;

    /** @var bool */
    protected $isSelect;

    /** @var ProductTypes */
    private $productTypes;

    /**
     * Category constructor.
     * @param \Meling\Filter                      $filter
     * @param \Meling\Filter\Sexes\Sex            $sex
     * @param \PHPixie\Slice\Type\ArrayData\Slice $data
     * @param mixed                               $id
     * @param string                              $name
     * @param bool                                $isSelect
     * @since 2.0
     */
    public function __construct(\Meling\Filter $filter, \Meling\Filter\Sexes\Sex $sex, \PHPixie\Slice\Type\ArrayData\Slice $data, $id, $name, $isSelect = false)
    {

        $this->filter   = $filter;
        $this->sex      = $sex;
        $this->data     = $data;
        $this->id       = $id;
        $this->name     = $name;
        $this->isSelect = $isSelect;
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
     * @return ProductTypes
     * @since 2.0
     */
    public function productTypes()
    {
        if($this->productTypes === null) {
            $this->productTypes = new ProductTypes($this->filter, $this, $this->data->arraySlice('productTypes'));
        }

        return $this->productTypes;
    }

}

