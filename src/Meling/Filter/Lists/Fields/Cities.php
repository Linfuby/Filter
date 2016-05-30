<?php
namespace Meling\Filter\Lists\Fields;

use PHPixie\Database\Type\SQL\Expression;

/**
 * Список Городов
 * Class Cities
 * @method Cities\City get($id)
 * @package Meling\Filter\Lists\Fields
 */
class Cities extends \Meling\Filter\Lists\FieldsOne
{
    protected function buildItem($item, $selected)
    {
        return new \Meling\Filter\Lists\Fields\Cities\City($item->id, $item->name, $selected);
    }

    /**
     * @return \PHPixie\Database\Driver\PDO\Query\Type\Select
     */
    protected function query()
    {
        if($this->query === null) {
            $this->query = $this->builder->connection()->selectQuery();
            // Идентфиикатор
            $this->query->fields(new Expression('DISTINCT(`cities`.`id`)'));
            // Название
            $this->query->fields('cities.name');
            // Таблица
            $this->query->table('cities');
            // Связь с магазинами
            $this->query->join('shops');
            $this->query->on('shops.cityId', 'cities.id');
            $this->query->where('shops.publish', 1);
            $this->query->where('shops.active', 1);
            $this->query->where('shops.hidden', 0);
            // Сортировка
            $this->query->orderAscendingBy('cities.name');
        }

        return $this->query;
    }

}
