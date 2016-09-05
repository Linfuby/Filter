<?php
namespace Meling\Filter\Locations;

/**
 * Class City
 * @package Meling\Filter\Sexes\Locations
 */
class City
{
    /** @var mixed */
    protected $id;

    /** @var string */
    protected $name;

    /** @var bool */
    protected $isSelect;

    /** @var Shop[] */
    protected $shops;

    /**
     * City constructor.
     * @param \Meling\Filter $filter
     * @param mixed          $id
     * @param string         $name
     * @param bool           $isSelect
     * @param array          $shops
     * @since    2.0
     */
    public function __construct(\Meling\Filter $filter, $id, $name, $isSelect = false, $shops = array())
    {
        $this->id       = $id;
        $this->name     = $name;
        $this->isSelect = $isSelect;
        $this->shops    = $shops;
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
     * @return Shop[]
     */
    public function shops()
    {
        return $this->shops;
    }

}

