<?php
namespace Meling\Filter\Lists\Fields;

use PHPixie\Database\Type\SQL\Expression;

/**
 * Список половой принадлежности
 * Class Sexes
 * @package Meling\Filter\Lists\Fields
 */
class Sexes extends \Meling\Filter\Lists\FieldsOne
{
    protected function buildItem($item, $selected)
    {
        return new \Meling\Filter\Lists\Fields\Sexes\Sex($item->id, $item->name, $selected);
    }

    /**
     * @return \PHPixie\Database\Driver\PDO\Query\Type\Select
     */
    protected function query()
    {
        if($this->query === null) {
            $this->query = $this->builder->connection()->selectQuery();
            // Идентфиикатор
            $this->query->fields(new Expression('DISTINCT(`id`)'));
            // Название
            $this->query->fields(array('name' => new Expression('IF(`id` = 3003, "Весь Ассортимент", CONCAT(UCASE(MID(`name`,1,1)),MID(`name`,2)))')));
            // Таблица
            $this->query->table('sexes');
            // Сортировка
            $this->query->orderAscendingBy('name');
        }

        return $this->query;
    }

}
