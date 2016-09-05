<?php
namespace Meling\Filter\Sexes\Categories\ProductTypes\Girths;

/**
 * Class Girth
 * @package Meling\Filter\Sexes\Categories\ProductTypes\Girths
 * @since   2.0
 */
class Girth
{
    /** @var \Meling\Filter\Sexes\Categories\ProductTypes\ProductType */
    protected $productType;

    /** @var mixed */
    protected $id;

    /** @var string */
    protected $name;

    /** @var bool */
    protected $isSelect;

    /**
     * Size constructor.
     * @param \Meling\Filter\Sexes\Categories\ProductTypes\ProductType $productType
     * @param mixed                                                      $id
     * @param string                                                     $name
     * @param bool                                                       $isSelect
     * @since 2.0
     */
    public function __construct(\Meling\Filter\Sexes\Categories\ProductTypes\ProductType $productType, $id, $name, $isSelect = false)
    {
        $this->productType = $productType;
        $this->id          = $id;
        $this->name        = $name;
        $this->isSelect    = $isSelect;
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
     * @return boolean
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
     * @return \Meling\Filter\Sexes\Categories\ProductTypes\ProductType
     * @since 2.0
     */
    public function productType()
    {
        return $this->productType;
    }

}

