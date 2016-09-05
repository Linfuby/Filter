<?php
namespace Meling\Tests\Filter;

/**
 * Class LocationsTest
 * Список локация должен возвращать
 * 1. Список городов со списком магазинов в каждом городе
 * 2. Выбранный город
 * 3. Выбранный магазин
 * 4. Город
 * @package Meling\Tests\Filter
 */
class LocationsTest extends \Meling\Tests\TestCase
{
    /**
     * @var \Meling\Filter\Locations
     */
    protected $locations;

    /**
     * @constructor
     */
    public function setUp()
    {
        $slice           = new \PHPixie\Slice();
        $data            = $slice->arrayData(['cityId' => '-8276106', 'shopId' => '802405521001']);
        $this->locations = new \Meling\Filter\Locations($this->getFilter(), $data->arraySlice());
    }

    /**
     * Список городов со списком магазинов в каждом городе
     */
    public function testAsArray()
    {
        foreach($this->locations->asArray() as $city) {
            $this->assertInstanceOf('Meling\Filter\Locations\City', $city);
            foreach($city->shops() as $shop) {
                $this->assertInstanceOf('Meling\Filter\Locations\Shop', $shop);
            }
        }
    }

    /**
     * Город
     */
    public function testGet()
    {
        $this->locations->asArray();
        $this->assertInstanceOf('Meling\Filter\Locations\City', $this->locations->get());
    }

    /**
     * Несуществующий город
     * @expectedException \Exception
     */
    public function testGetThrow()
    {
        $slice           = new \PHPixie\Slice();
        $data            = $slice->arrayData();
        $this->locations = new \Meling\Filter\Locations(
            $this->getFilter(), $data->arraySlice()
        );
        $this->locations->get();
    }

    /**
     * Выбранный город
     */
    public function testSelectedCityId()
    {
        $this->assertEquals('-8276106', $this->locations->cityId());
    }

    /**
     * Выбранный магазин
     */
    public function testSelectedShopId()
    {
        $this->assertEquals('802405521001', $this->locations->shopId());
    }
}
