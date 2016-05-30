<?php
namespace Meling\Filter\Lists\Fields\Shops;

/**
 * Class Shop
 * @package Meling\Filter\Lists\Fields\Shops
 */
class Shop extends \Meling\Filter\Lists\Fields\One
{
    /**
     * @var float
     */
    protected $lat;

    /**
     * @var float
     */
    protected $lng;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var string
     */
    protected $phone;

    /**
     * @var string
     */
    protected $times;

    /**
     * @var \Meling\Filter\Lists\Fields\Types\Extra\Attributes\Attribute
     */
    private $city;

    /**
     * Value constructor.
     * @param string                                  $id
     * @param string                                  $name
     * @param string                                  $address
     * @param string                                  $phone
     * @param string                                  $times
     * @param float                                   $lat
     * @param float                                   $lng
     * @param bool                                    $checked
     * @param \Meling\Filter\Lists\Fields\Cities\City $city
     */
    public function __construct($id, $name, $address, $phone, $times, $lat, $lng, $checked, \Meling\Filter\Lists\Fields\Cities\City $city)
    {
        parent::__construct($id, $name, $checked);
        $this->address = $address;
        $this->phone   = $phone;
        $this->times   = $times;
        $this->lat     = $lat;
        $this->lng     = $lng;
        $this->city    = $city;
    }

    /**
     * @return string
     */
    public function address()
    {
        return $this->address;
    }

    public function city()
    {
        return $this->city;
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
    public function phone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function times()
    {
        return $this->times;
    }


}
