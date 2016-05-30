<?php
namespace Meling\Filter\Lists\Fields;

use PHPixie\Database\Type\SQL\Expression;

/**
 * Список Акций
 * Class Actions
 * @package Meling\Filter\Lists\Fields
 */
class Actions extends \Meling\Filter\Lists\FieldsOne
{
    protected function buildItem($item, $selected)
    {
        return new \Meling\Filter\Lists\Fields\Actions\Action($item->id, $item->name, $selected);
    }

    /**
     * @return \PHPixie\Database\Driver\PDO\Query\Type\Select
     */
    protected function query()
    {
        if($this->query === null) {
            $this->query = $this->builder->connection()->selectQuery();
            // Идентфиикатор
            $this->query->fields(new Expression('DISTINCT(`actions`.`id`)'));
            // Название
            $this->query->fields('actions.name');
            // Таблица
            $this->query->table('allowactions', 'actions');
            // Сортировка
            $this->query->orderAscendingBy('actions.date_start');
        }

        return $this->query;
    }

}
