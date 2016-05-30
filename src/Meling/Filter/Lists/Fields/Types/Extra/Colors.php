<?php
namespace Meling\Filter\Lists\Fields\Types\Extra;

use PHPixie\Database\Type\SQL\Expression;

class Colors extends ExtraList
{
    public function query($exclude = null)
    {
        $query = $this->type->builder()->connection()->selectQuery();
        // Идентфиикатор
        $query->fields(new Expression('DISTINCT(`colors`.`id`)'));
        // Название
        $query->fields('colors.name');
        // Таблица
        $query->table('colors');
        $this->type->builder()->products()->joins($query, true);
        // Связь с Таблицей цветов
        $query->join(strtolower('optionsColors'), 'optionsColors');
        $query->on('optionsColors.colorId', 'colors.id');
        // Связь с опциями
        $this->type->builder()->products()->join($query, 'allowOptions', 'id', 'optionsColors.optionId');
        // Связь с Товарами
        $query->join(strtolower('allowProducts'), 'products');
        $query->on('products.id', 'allowOptions.productId');
        // Обновляем запрос (Добавление обязательных условий из основного фильтра)
        $this->type->builder()->products()->updateQueryWithProducts($query, $exclude);
        // Ограничение по Типу изделия
        $query->where('products.productTypeId', $this->type->id());
        // Сортировка
        $query->orderAscendingBy('colors.name');

        return $query;
    }

    protected function buildItem($item, $checked, $disabled)
    {
        return new Colors\Color($item->id, $item->name, $checked, $disabled);
    }

}
