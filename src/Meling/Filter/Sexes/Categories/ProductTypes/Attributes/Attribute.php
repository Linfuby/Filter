<?php
namespace Meling\Filter\Sexes\Categories\ProductTypes\Attributes;

/**
 * Class Attribute
 * @package Meling\Filter\Sexes\Categories\ProductTypes\Attributes
 * @since   2.0
 */
class Attribute
{
    /** @var \Meling\Filter\Sexes\Categories\ProductTypes\ProductType */
    protected $productType;

    /** @var mixed */
    protected $id;

    /** @var string */
    protected $name;

    /** @var AttributeValue[] */
    protected $attributeValues;

    /** @var array */
    protected $isSelect;

    /**
     * Size constructor.
     * @param \Meling\Filter\Sexes\Categories\ProductTypes\ProductType $productType
     * @param mixed                                                      $id
     * @param string                                                     $name
     * @param array                                                      $attributeValues
     * @param array                                                      $isSelect
     * @since    2.0
     */
    public function __construct(\Meling\Filter\Sexes\Categories\ProductTypes\ProductType $productType, $id, $name, array $attributeValues = array(), $isSelect = array())
    {
        $this->productType     = $productType;
        $this->id              = $id;
        $this->name            = $name;
        $this->attributeValues = $attributeValues;
        $this->isSelect        = $isSelect;
    }

    /**
     * @return AttributeValue[]
     * @since 2.0
     */
    public function attributeValues()
    {
        return $this->attributeValues;
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
     * @return array
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

