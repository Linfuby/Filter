<?php
namespace Meling\Filter\Locations;

/**
 * Class Shop
 * @package Meling\Filter\Locations
 * @since   2.0
 */
class Shop
{
    /** @var mixed */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    private $street;

    /** @var string */
    private $phone;

    /** @var string */
    private $work_times;

    /** @var float */
    private $lat;

    /** @var float */
    private $lng;

    /** @var bool */
    private $isSelect;

    /**
     * Shop constructor.
     * @param mixed  $id
     * @param string $name
     * @param string $street
     * @param string $phone
     * @param string $work_times
     * @param double $lat
     * @param double $lng
     * @param bool   $isSelect
     * @since 2.0
     */
    public function __construct($id, $name, $street, $phone, $work_times, $lat, $lng, $isSelect = false)
    {
        $this->id         = $id;
        $this->name       = $name;
        $this->street     = $street;
        $this->phone      = $phone;
        $this->work_times = $work_times;
        $this->lat        = $lat;
        $this->lng        = $lng;
        $this->isSelect   = $isSelect;
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isSelect()
    {
        return $this->isSelect;
    }

    /**
     * @return float
     */
    public function lat()
    {
        return $this->lat;
    }

    /**
     * @return float
     */
    public function lng()
    {
        return $this->lng;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function phone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function street()
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function work_times()
    {
        return $this->work_times;
    }

}

