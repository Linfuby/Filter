<?php
namespace Meling\Filter\Lists\Fields;

use PHPixie\Database\Type\SQL\Expression;

/**
 * Список Типов изделий
 * Class Types
 * @method Types\Type[] asArray()
 * @method Types\Type get($id)
 * @package Meling\Filter\Lists\Fields
 */
class Types extends \Meling\Filter\Lists\FieldsMany
{
    /**
     * @var \PHPixie\Slice\Type\ArrayData\Slice
     */
    protected $extraIds;

    /**
     * FieldList constructor.
     * @param \Meling\Filter\Builder              $builder Строитель
     * @param string                              $name    Название
     * @param array                               $ids     Выбранные пункты
     * @param \PHPixie\Slice\Type\ArrayData\Slice $extraIds
     */
    public function __construct(\Meling\Filter\Builder $builder, $name, array $ids, \PHPixie\Slice\Type\ArrayData\Slice $extraIds)
    {
        parent::__construct($builder, $name, $ids);
        $this->extraIds = $extraIds;
    }

    /**
     * @param bool $skipEmpty
     * @return Categories\Category[]
     */
    public function categories($skipEmpty = false)
    {
        /** @var \Meling\Filter\Lists\Fields\Categories\Category[] $categories */
        $categories = array();
        $typeIds    = $this->builder->data()->get('types');
        foreach($this->asArray() as $type) {
            if(!array_key_exists($type->category()->id(), $categories)) {
                $type->category()->clearTypes();
                if($skipEmpty && $typeIds) {
                    if(!in_array($type->id(), $typeIds)) {
                        continue;
                    }
                }
                $categories[$type->category()->id()] = $type->category();
            }
            $categories[$type->category()->id()]->addType($type);
        }

        return $categories;
    }

    /**
     * @return Types\Type
     * @throws \Exception
     */
    public function defaultType()
    {
        try {
            $ids = array_keys($this->asArray());

            return $this->get(current($ids));
        } catch(\Exception $e) {
        }
    }

    /**
     * @return \PHPixie\Slice\Type\ArrayData\Slice
     */
    public function extraIds()
    {
        return $this->extraIds;
    }

    /**
     * @inheritdoc
     */
    protected function buildItem($item, $checked = false)
    {
        return new \Meling\Filter\Lists\Fields\Types\Type($item->id, $item->name, $checked, $this->builder, $this->builder->categories()->get($item->categoryId), $this->extraIds);
    }

    /**
     * @return \PHPixie\Database\Driver\PDO\Query\Type\Select
     */
    protected function query()
    {
        if($this->query === null) {
            $this->query = $this->builder->connection()->selectQuery();
            // Идентфиикатор
            $this->query->fields(new Expression('DISTINCT(types.id)'));
            // Название
            $this->query->fields('types.name');
            // Идентификатор категории
            $this->query->fields('types.categoryId');
            // Таблица
            $this->query->table(strtolower('productTypes'), 'types');
            // Связь с Товарами
            $this->query->join(strtolower('allowProducts'), 'products');
            $this->query->on('products.productTypeId', 'types.id');
            $this->query->join(strtolower('categories'), 'categories');
            $this->query->on('categories.id', 'types.categoryId');
            // Исключить типы из неопубликованных категорий
            $this->query->where('categories.publish', 1);
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
            // Ограничение по Брендам
            if($brandsIds = $this->builder->brands()->ids()) {
                $this->query->where('products.brandId', 'in', $brandsIds);
            }
            // Ограничение по Сезонам
            if($seasonsIds = $this->builder->seasons()->ids()) {
                $this->query->where('products.seasonId', 'in', $seasonsIds);
            }
            $this->builder->products()->joins($this->query, true);
            $this->builder->products()->join($this->query, 'allowOptions', 'productId', 'products.id');
            // Ограничение по городу
            if($cityId = $this->builder->cities()->id()) {
                $this->builder->products()->join($this->query, 'restOptions', 'optionId', 'allowOptions.id');
                $this->builder->products()->join($this->query, 'shops', 'id', 'restOptions.shopId');
                $this->query->where('shops.cityId', $cityId);
            }
            // Ограничение по магазину
            if($shopId = $this->builder->shops()->id()) {
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
            // Сортировка
            $this->query->orderAscendingBy('types.name');
        }

        return $this->query;
    }

}
