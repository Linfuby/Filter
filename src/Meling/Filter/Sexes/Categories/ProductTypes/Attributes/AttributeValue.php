<?php
namespace Meling\Filter\Sexes\Categories\ProductTypes\Attributes;

/**
 * Class AttributeValue
 * @package Meling\Filter\Sexes\Categories\ProductTypes\Attributes
 * @since   2.0
 */
class AttributeValue
{
    /** @var mixed */
    protected $id;

    /** @var string */
    protected $name;

    /** @var bool */
    protected $isSelect;

    /**
     * AttributeValue constructor.
     * @param mixed  $id
     * @param string $name
     * @param bool   $isSelect
     * @since 2.0
     */
    public function __construct($id, $name, $isSelect = false)
    {
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

}

