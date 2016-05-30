<?php
namespace Meling\Filter\Lists\Fields;

use PHPixie\Database\Type\SQL\Expression;

/**
 * Class Prices
 * @package Meling\Filter\Lists\Fields
 */
class Prices
{
    /**
     * Строитель
     * @var \Meling\Filter\Builder
     */
    protected $builder;

    /**
     * Выбранный пункт фильтрации
     * @var mixed
     */
    protected $from;

    protected $to;

    /**
     * @var int
     */
    protected $max;

    /**
     * @var int
     */
    protected $min;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \PHPixie\Database\Driver\PDO\Query\Type\Select
     */
    protected $query;

    protected $id;

    /**
     * FieldList constructor.
     * @param \Meling\Filter\Builder $builder Строитель
     * @param string                 $name    Название
     * @param null                   $from
     * @param null                   $to
     * @param null                   $id
     */
    public function __construct(\Meling\Filter\Builder $builder, $name, $from = null, $to = null, $id = null)
    {
        $this->builder = $builder;
        $this->name    = $name;
        $this->from    = $from;
        $this->to      = $to;
        $this->id      = $id;
    }

    public function from()
    {
        if($this->from < $this->min()) {
            $this->from = $this->min();
        }

        return str_replace(' ', '', $this->from);
    }

    public function id()
    {
        return $this->id;
    }

    public function max()
    {
        $this->requirePrices();

        return $this->max;
    }

    public function min()
    {
        $this->requirePrices();

        return $this->min;
    }

    public function to()
    {
        if($this->to > $this->max()) {
            $this->to = $this->max();
        }

        return str_replace(' ', '', $this->to);
    }

    /**
     * @return \PHPixie\Database\Driver\PDO\Query\Type\Select
     */
    protected function query()
    {
        if($this->query === null) {
            $this->query = $this->builder->connection()->selectQuery();
            // Идентфиикатор
            $this->query->fields(array('priceMax' => new Expression('MAX(`' . strtolower('allowOptions') . '`.`price`)')));
            $this->query->fields(array('priceMin' => new Expression('MIN(`' . strtolower('allowOptions') . '`.`price`)')));
            // Таблица
            $this->query->table(strtolower('allowOptions'));
            // Связь с Товарами
            $this->query->join(strtolower('allowProducts'), 'products');
            $this->query->on('products.id', strtolower('allowOptions') . '.productId');
            // Только с доступными товарами
            $this->builder->products()->updateQueryWithProducts($this->query, 'prices');
        }

        return $this->query;
    }

    /**
     * Формирование списка пунктов
     */
    protected function requirePrices()
    {
        if($this->min !== null) {
            return;
        }
        // Выполняем запрос к БД
        $prices    = $this->query()->execute()->current();
        $this->max = $prices->priceMax;
        $this->min = $prices->priceMin;
    }

}
