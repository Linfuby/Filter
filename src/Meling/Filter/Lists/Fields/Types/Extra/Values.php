<?php
namespace Meling\Filter\Lists\Fields\Types\Extra;

use PHPixie\Database\Type\SQL\Expression;

/**
 * Class Values
 * @method Values\Value[] asArray()
 * @method Values\Value get($id)
 * @package Meling\Filter\Lists\Fields\Types\Extra
 */
class Values extends ExtraList
{
    /**
     * @var \PHPixie\Slice\Type\ArrayData\Slice
     */
    protected $data;

    /**
     * @var array
     */
    protected $ids;

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

    public function attributes()
    {
        /** @var \Meling\Filter\Lists\Fields\Types\Extra\Attributes\Attribute[] $attributes */
        $attributes = array();
        foreach($this->asArray() as $value) {
            if(!array_key_exists($value->attribute()->id(), $attributes)) {
                $attributes[$value->attribute()->id()] = $value->attribute();
            }
            $attributes[$value->attribute()->id()]->addValue($value);
        }

        return $attributes;
    }

    public function ids()
    {
        if($this->ids === null) {
            $this->ids = array();
            foreach($this->data->keys() as $key) {
                if($key != 'sizes' && $key != 'girths' && $key != 'colors') {
                    $this->ids = array_merge($this->ids, $this->data->get($key));
                }
            }
        }

        return $this->ids;
    }

    public function query($exclude = null)
    {
        $query = $this->type->builder()->connection()->selectQuery();
        // Идентфиикатор
        $query->fields(new Expression('DISTINCT(`attributeValues`.`id`)'));
        // Название
        $query->fields('attributeValues.name');
        $query->fields('attributeValues.attributeId');
        // Таблица
        $query->table(strtolower('attributeValues'), 'attributeValues');
        $this->type->builder()->products()->joins($query, true);
        // Связь с таблицей атрибутов
        $query->join(strtolower('productsAttributeValues'), 'pAttributeValues');
        $query->on('pAttributeValues.attributeValueId', 'attributeValues.id');
        // Связь с Товарами
        $query->join(strtolower('allowProducts'), 'products');
        $query->on('products.id', 'pAttributeValues.productId');
        // Обновляем запрос (Добавление обязательных условий из основного фильтра)
        $this->type->builder()->products()->updateQueryWithProducts($query, $exclude);
        if($exclude){
            //$query->orWhere('attributeValues.attributeId', $this->type->builder()->data()->get('extraName'));
        }
        // Ограничение по Типу изделия
        $query->where('products.productTypeId', $this->type->id());
        // Сортировка
        $query->orderAscendingBy('attributeValues.name');

        return $query;
    }

    protected function buildItem($item, $checked, $disabled)
    {
        return new Values\Value($item->id, $item->name, $checked, $disabled, $this->type->attributes()->get($item->attributeId));
    }

}
