<?php
namespace Meling;

/**
 * Class Filter
 * Фильтр
 * Список Половой принадлежности (Женское, Мужское, Весь Ассортимент)
 * Список Локаций (Город, Магазин, Весь Ассортимент)
 * Список Акций (Распродажа, Акция, Весь Ассортимент)
 * Список Товаров (Идентификаторы товаров)
 * Для каждого пола, необходимо получить список Категорий, Брендов, Сезонов, Цветов, Цены
 * Для каждой категории необходимо получить список Типов изделий
 * Для каждого типа изделия, необходимо получить Размеры, Чашки, Цвета, Цены, Атрибуты и их Значения
 * @package Meling
 * @since   2.0
 */
class Filter
{
    /**
     * Соединение с БД
     * @var \PHPixie\Database\Connection
     * @since 2.0
     */
    protected $connection;

    /**
     * Выбранные пункты в фильтре
     * @var \PHPixie\Slice\Type\ArrayData\Editable
     * @since 2.0
     */
    protected $data;

    /**
     * Список Акций
     * @var Filter\Actions
     * @since 2.0
     */
    private $actions;

    /**
     * Список Половой принадлежности
     * @var Filter\Sexes
     * @since 2.0
     */
    private $sexes;

    /**
     * Список Локаций
     * @var Filter\Locations
     * @since 2.0
     */
    private $locations;

    /**
     * Класс товаров
     * @var Filter\Products
     * @since 2.0
     */
    private $products;

    /**
     * Filter constructor.
     * @param \PHPixie\Database\Connection           $connection Соединение с БД
     * @param \PHPixie\Slice\Type\ArrayData\Editable $data       Выбранные пункты в фильтре
     * @since 2.0
     */
    public function __construct(\PHPixie\Database\Connection $connection, \PHPixie\Slice\Type\ArrayData\Editable $data)
    {
        $this->connection = $connection;
        $this->data       = $data;
    }

    /**
     * @return Filter\Actions
     * @since 2.0
     */
    public function actions()
    {
        if($this->actions === null) {
            $this->actions = new Filter\Actions($this, $this->data->get('actions', []));
        }

        return $this->actions;
    }

    /**
     * @return \PHPixie\Database\Connection
     * @since 2.0
     */
    public function connection()
    {
        return $this->connection;
    }

    /**
     * @return \PHPixie\Slice\Type\ArrayData\Editable
     * @since 2.0
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return Filter\Locations
     * @since 2.0
     */
    public function locations()
    {
        if($this->locations === null) {
            $this->locations = new Filter\Locations($this, $this->data->arraySlice('locations'));
        }

        return $this->locations;
    }

    /**
     * @return Filter\Products
     * @since 2.0
     */
    public function products()
    {
        if($this->products === null) {
            $this->products = new Filter\Products($this);
        }

        return $this->products;
    }

    /**
     * @return Filter\Sexes
     * @since 2.0
     */
    public function sexes()
    {
        if($this->sexes === null) {
            $this->sexes = new Filter\Sexes($this, $this->data->arraySlice('sexes'));
        }

        return $this->sexes;
    }

}

