<?php
namespace Meling\Filter\Lists;

/**
 * Class FieldsOne
 * @package Meling\Filter\Lists
 */
abstract class FieldsOne
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
    protected $id;

    /**
     * Все пункты фильтрации
     * @var Fields\One[]
     */
    protected $items;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \PHPixie\Database\Driver\PDO\Query\Type\Select
     */
    protected $query;

    /**
     * FieldList constructor.
     * @param \Meling\Filter\Builder $builder Строитель
     * @param string                 $name    Название
     * @param string                 $id      Выбранные пункты
     */
    public function __construct(\Meling\Filter\Builder $builder, $name, $id = null)
    {
        $this->builder = $builder;
        $this->name    = $name;
        $this->id      = $id;
    }

    /**
     * @return Fields\One[]
     */
    public function asArray()
    {
        $this->requireItems();

        return $this->items;
    }

    public function get($id)
    {
        $this->requireItems();
        if(array_key_exists($id, $this->items)) {
            return $this->items[$id];
        }

        throw new \Exception("Field '$id' does not exist");
    }

    public function id()
    {
        return $this->id;
    }

    /**
     * Формирование списка пунктов
     */
    protected function requireItems()
    {
        if($this->items !== null) {
            return;
        }
        $items = array();
        // Выполняем запрос к БД
        foreach($this->query()->execute() as $item) {
            // По умолчанию пункт не выбран
            $selected = false;
            // Если пункт совпадает с выбранным
            if($item->id == $this->builder->data()->get($this->name)) {
                // Отмечаем пункт как выбранный
                $selected = true;
            }
            $items[$item->id] = $this->buildItem($item, $selected);
        }

        $this->items = $items;
    }

    /**
     * @param object $item
     * @param bool   $selected
     * @return \Meling\Filter\Lists\Fields\One
     */
    protected abstract function buildItem($item, $selected);

    /**
     * @return \PHPixie\Database\Driver\PDO\Query\Type\Select
     */
    protected abstract function query();

}