<?php
namespace Meling\Filter\Lists\Fields\Types\Extra;

use PHPixie\Database\Type\SQL\Expression;

class Sizes extends ExtraList
{
    public function query($exclude = null)
    {
        $query = $this->type->builder()->connection()->selectQuery();
        // Идентфиикатор
        $query->fields(new Expression('DISTINCT(`sizes`.`id`)'));
        // Название
        $query->fields('sizes.name');
        // Таблица
        $query->table('sizes');
        $this->type->builder()->products()->joins($query, true);
        // Связь с опциями
        $this->type->builder()->products()->join($query, 'allowOptions', 'sizeId', 'sizes.id');
        // Связь с Товарами
        $query->join(strtolower('allowProducts'), 'products');
        $query->on('products.id', 'allowOptions.productId');
        // Обновляем запрос (Добавление обязательных условий из основного фильтра)
        $this->type->builder()->products()->updateQueryWithProducts($query, $exclude);
        // Ограничение по Типу изделия
        $query->where('products.productTypeId', $this->type->id());
        // Сортировка
        $query->orderAscendingBy('sizes.name');

        return $query;
    }

    protected function buildItem($item, $checked, $disabled)
    {
        return new Sizes\Size($item->id, $item->name, $checked, $disabled);
    }

}
