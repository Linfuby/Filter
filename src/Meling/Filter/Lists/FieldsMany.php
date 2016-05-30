<?php
namespace Meling\Filter\Lists;

/**
 * Class FieldsMany
 * @package Meling\Filter\Lists
 */
abstract class FieldsMany
{
    /**
     * Строитель
     * @var \Meling\Filter\Builder
     */
    protected $builder;

    /**
     * Выбранные пункт фильтрации
     * @var array
     */
    protected $ids;

    /**
     * Все пункты фильтрации
     * @var Fields\Many[]
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
     * @param array                  $ids     Выбранные пункты
     */
    public function __construct(\Meling\Filter\Builder $builder, $name, $ids = array())
    {
        $this->builder = $builder;
        $this->name    = $name;
        $this->ids     = $ids;
    }

    /**
     * @return Fields\Many[]
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

    public function ids()
    {
        return array_intersect($this->ids, array_keys($this->asArray()));
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
            $checked = false;
            // Если пункт совпадает с выбранным
            if(in_array($item->id, $this->builder->data()->get($this->name, array()))) {
                // Отмечаем пункт как выбранный
                $checked = true;
            }
            $items[$item->id] = $this->buildItem($item, $checked);
        }
        $this->items = $items;
    }

    /**
     * @param object $item
     * @param bool   $checked
     * @return \Meling\Filter\Lists\Fields\Many
     */
    protected abstract function buildItem($item, $checked);

    /**
     * @return \PHPixie\Database\Driver\PDO\Query\Type\Select
     */
    protected abstract function query();

}