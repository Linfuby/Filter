<?php
namespace Meling\Filter\Lists\Fields;

use PHPixie\Database\Type\SQL\Expression;

/**
 * Список Сезонов
 * Class Seasons
 * @method Seasons\Season get($id)
 * @package Meling\Filter\Lists\Fields
 */
class Seasons extends \Meling\Filter\Lists\FieldsMany
{
    protected function buildItem($item, $checked)
    {
        return new \Meling\Filter\Lists\Fields\Seasons\Season($item->id, $item->name, $checked);
    }

    /**
     * @return \PHPixie\Database\Driver\PDO\Query\Type\Select
     */
    protected function query()
    {
        if($this->query === null) {
            $this->query = $this->builder->connection()->selectQuery();
            // Идентфиикатор
            $this->query->fields(new Expression('DISTINCT(seasons.id)'));
            // Название
            $this->query->fields('seasons.name');
            // Таблица
            $this->query->table('seasons');
            // Связь с Товарами
            $this->query->join(strtolower('allowProducts'), 'products');
            $this->query->on('products.seasonId', 'seasons.id');
            // Связь с Типами Изделий
            $this->query->join(strtolower('productTypes'), 'types');
            $this->query->on('types.id', 'products.productTypeId');
            // Ограничение по половой принадлежности
            if($sexId = $this->builder->sexes()->id()) {
                if($sexId != 3003) {
                    $this->query->where('products.sexId', $sexId);
                }
            }
            // Ограничение по категориям
            if($categoriesIds = $this->builder->categories()->ids()) {
                $this->query->where('types.categoryId', 'in', $categoriesIds);
            }
            // Ограничение по типам изделий
            if($typesIds = $this->builder->types()->ids()) {
                $this->query->where('products.productTypeId', 'in', $typesIds);
            }
            // Ограничение по Брендам
            if($brandsIds = $this->builder->brands()->ids()) {
                $this->query->where('products.brandId', 'in', $brandsIds);
            }
            $this->builder->products()->joins($this->query, true);
            // Ограничение по городу
            if($cityId = $this->builder->cities()->id()) {
                $this->builder->products()->join($this->query, 'allowOptions', 'productId', 'products.id');
                $this->builder->products()->join($this->query, 'restOptions', 'optionId', 'allowOptions.id');
                $this->builder->products()->join($this->query, 'shops', 'id', 'restOptions.shopId');
                $this->query->where('shops.cityId', $cityId);
            }
            // Ограничение по магазину
            if($shopId = $this->builder->shops()->id()) {
                $this->builder->products()->join($this->query, 'allowOptions', 'productId', 'products.id');
                $this->builder->products()->join($this->query, 'restOptions', 'optionId', 'allowOptions.id');
                $this->query->where('restOptions.shopId', $shopId);
            }
            // Ограничение по магазину
            if($actionId = $this->builder->actions()->id()) {
                if($actionId === 'sale') {
                    $this->builder->products()->join($this->query, 'allowOptions', 'productId', 'products.id');
                    $this->query->where('allowOptions.special', 1);
                    $this->query->where('allowOptions.old_price', '>', 'allowOptions.price');
                } else {
                    $action = $this->builder->connection()->selectQuery()->table('actions')->where('id', $actionId)->execute()->current();
                    if($action) {
                        switch($action->actionTypeId) {
                            case '53001';
                            case '53005';
                            case '53014';
                                $this->builder->products()->join($this->query, 'allowOptions', 'productId', 'products.id');
                                $this->builder->products()->join($this->query, 'actionProducts', 'optionId', 'allowOptions.id');
                                $this->query->where('actionProducts.actionId', $action->id);
                                break;
                            case '53006';
                            case '53007';
                            case '53008';
                            case '53009';
                            case '53010';
                                $this->builder->products()->join($this->query, 'allowOptions', 'productId', 'products.id');
                                switch($action->price_flag) {
                                    case '0':
                                        $this->query->where('allowOptions.special', 0);
                                        break;
                                    case '2':
                                        $this->query->where('allowOptions.special', 1);
                                        break;
                                }
                        }
                    }
                }
            }
            // Только опубликованные
            $this->query->where('seasons.publish', 1);
            // Сортировка
            $this->query->orderDescendingBy('seasons.ordering');
        }

        return $this->query;
    }

}
