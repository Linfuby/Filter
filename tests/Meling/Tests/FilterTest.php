<?php
namespace Meling\Tests;

/**
 * Class FilterTest
 * Фильтр должен возвращать
 * Список Половой принадлежности (Женское, Мужское, Весь Ассортимент)
 * Список Локаций (Город, Магазин, Весь Ассортимент)
 * Список Акций (Распродажа, Акция, Весь Ассортимент)
 * Список Товаров (Идентификаторы товаров)
 * @package Meling\Tests
 */
class FilterTest extends TestCase
{
    /**
     * @var \Meling\Filter
     */
    protected $filter;

    public function setUp()
    {
        $this->filter = $this->getFilter();
    }

    /**
     * Список Акций (Распродажа, Акция, Весь Ассортимент)
     */
    public function testActions()
    {
        $this->assertInstanceOf('\Meling\Filter\Actions', $this->filter->actions());
    }

    /**
     * Соединение с БД
     */
    public function testConnection()
    {
        $this->assertInstanceOf('\PHPixie\Database\Connection', $this->filter->connection());
    }

    /**
     * Выбранные данные
     */
    public function testData()
    {
        $this->assertInstanceOf('\PHPixie\Slice\Type\ArrayData\Editable', $this->filter->data());
    }

    /**
     * Список Локаций (Город, Магазин, Весь Ассортимент)
     */
    public function testLocations()
    {
        $this->assertInstanceOf('\Meling\Filter\Locations', $this->filter->locations());
    }

    /**
     * Список Товаров (Идентификаторы товаров)
     */
    public function testProducts()
    {
        $this->assertInstanceOf('\Meling\Filter\Products', $this->filter->products());
    }

    /**
     * Список Половой принадлежности (Женское, Мужское, Весь Ассортимент)
     */
    public function testSexes()
    {
        $this->assertInstanceOf('\Meling\Filter\Sexes', $this->filter->sexes());
    }

}

