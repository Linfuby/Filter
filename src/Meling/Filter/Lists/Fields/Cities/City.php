<?php
namespace Meling\Filter\Lists\Fields\Cities;

/**
 * Class City
 * @package Meling\Filter\Lists\Fields\Cities
 */
class City extends \Meling\Filter\Lists\Fields\One
{
    /**
     * @var \Meling\Filter\Lists\Fields\Shops\Shop[]
     */
    protected $shops = array();

    /**
     * @param \Meling\Filter\Lists\Fields\Shops\Shop $shop
     */
    public function addValue($shop)
    {
        $this->shops[$shop->name()] = $shop;
    }

    /**
     * @return \Meling\Filter\Lists\Fields\Shops\Shop[]
     */
    public function shops()
    {
        return $this->shops;
    }

}
