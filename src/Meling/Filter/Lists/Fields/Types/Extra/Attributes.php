<?php
namespace Meling\Filter\Lists\Fields\Types\Extra;

use PHPixie\Database\Type\SQL\Expression;

/**
 * Class Attributes
 * @method Attributes\Attribute[] asArray()
 * @method Attributes\Attribute get($id)
 * @package Meling\Filter\Lists\Fields\Types\Extra
 */
class Attributes extends ExtraList
{
    /**
     * @var \PHPixie\Slice\Type\ArrayData\Slice
     */
    protected $data;

    /**
     * Sizes constructor.
     * @param \Meling\Filter\Lists\Fields\Types\Type $type
     * @param string                                 $name
     * @param \PHPixie\Slice\Type\ArrayData\Slice    $data
     */
    public function __construct(\Meling\Filter\Lists\Fields\Types\Type $type, $name, $data)
    {
        parent::__construct($type, $name, array());
        $this->data = $data;
    }

    public function ids()
    {
        return $this->data->keys();
    }

    public function query($exclude = null)
    {
        \PHPixie\Debug::logTrace();
        $query = $this->type->builder()->connection()->selectQuery();
        // Идентфиикатор
        $query->fields(new Expression('DISTINCT(`attributes`.`id`)'));
        // Название
        $query->fields('attributes.name');
        // Таблица
        $query->table('attributes');
        $this->type->builder()->products()->joins($query, true);
        // Связь с таблицей атрибутов
        $query->join(strtolower('productsAttributes'), 'productsAttributes');
        $query->on('productsAttributes.attributeId', 'attributes.id');
        // Связь с Товарами
        $query->join(strtolower('allowProducts'), 'products');
        $query->on('products.id', 'productsAttributes.productId');
        // Обновляем запрос (Добавление обязательных условий из основного фильтра)
        $this->type->builder()->products()->updateQueryWithProducts($query);
        // Ограничение по Типу изделия
        $query->where('products.productTypeId', $this->type->id());
        // Сортировка
        $query->orderAscendingBy('attributes.name');

        return $query;
    }

    protected function buildItem($item, $checked, $disabled)
    {
        return new Attributes\Attribute($item->id, $item->name, $checked, $disabled);
    }

    protected function requireItems()
    {
        if($this->items !== null) {
            return;
        }
        /** @var \Meling\Filter\Lists\Fields\Types\Extra[] $items */
        $items = array();
        foreach($this->query()->execute() as $item) {
            $items[$item->id] = $this->buildItem($item, in_array($item->id, $this->ids()), true);
        }
        $this->items = $items;
    }

}
