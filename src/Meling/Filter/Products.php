<?php
namespace Meling\Filter;

use PHPixie\Database\Type\SQL\Expression;

class Products
{
    /**
     * @var \PHPixie\Database\Driver\PDO\Query\Type\Select
     */
    protected $query;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var array
     */
    protected $ids;

    /**
     * Подключенные таблицы методом JOIN
     * @var array
     */
    protected $joins = array();

    /**
     * Products constructor.
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function ids()
    {
        if($this->ids === null) {
            $this->ids = $this->query()->execute()->getField('id');
        }

        return $this->ids;
    }

    /**
     * @param \PHPixie\Database\Driver\PDO\Query\Type\Select $query
     * @param string                                         $table
     * @param string                                         $key
     * @param string                                         $foreignKey
     * @param string                                         $type
     */
    public function join($query, $table, $key, $foreignKey, $type = 'inner')
    {
        $this->joins($query);
        $from = $query->getTable();
        if($from['table'] !== strtolower($table) && !array_key_exists($table, $this->joins[$from['table']])) {
            $query->join(strtolower($table), $table, $type);
            $query->on($table . '.' . $key, $foreignKey);
            $this->joins[$from['table']][$table] = $table;
        }
    }

    /**
     * @param \PHPixie\Database\Driver\PDO\Query\Type\Select $query
     * @param bool                                           $reset
     */
    public function joins($query, $reset = false)
    {
        // Формируем список всех подключенных таблиц через Join
        $from = $query->getTable();
        if($reset) {
            $this->joins[$from['table']] = array();
        }
        if(array_key_exists($from['table'], $this->joins)) {
            return;
        }
        $joins = array($from['table'] => array());
        foreach($query->getJoins() as $join) {
            $this->joins[$from['table']][$join['alias']] = $join['alias'];
        }
        $this->joins = $joins;
    }

    public function query()
    {
        if($this->query === null) {
            $this->query = $this->builder->connection()->selectQuery();
            // Идентфиикатор
            $this->query->fields(new Expression('DISTINCT(`products`.`id`)'));
            // Таблица
            $this->query->table(strtolower('allowProducts'), 'products');
            $this->join($this->query, 'allowOptions', 'productId', 'products.id');
            $this->join($this->query, 'productTypes', 'id', 'products.productTypeId');
            $this->join($this->query, 'categories', 'id', 'productTypes.categoryId');
            $this->query->where('categories.publish', 1);
            // Обновляем запрос (Добавление обязательных условий из основного фильтра)
            $this->updateQueryWithProducts($this->query, 'products');
        }

        return $this->query;
    }

    /**
     * @param \PHPixie\Database\Driver\PDO\Query\Type\Select $query
     * @param null                                           $exclude
     * @return array
     */
    public function updateQueryWithProducts($query, $exclude = null)
    {
        // Ограничение по половой принадлежности
        if($sexId = $this->builder->sexes()->id()) {
            if($sexId != 3003) {
                $query->where('products.sexId', $sexId);
            }
        }
        // Ограничение по категориям
        if($categoriesIds = $this->builder->categories()->ids()) {
            // Связь с Типами Изделий
            $this->join($query, 'productTypes', 'id', 'products.productTypeId');
            $query->where('productTypes.categoryId', 'in', $categoriesIds);
        }
        // Ограничение по типам изделий
        if($typesIds = $this->builder->types()->ids()) {
            $query->where('products.productTypeId', 'in', $typesIds);
        }
        // Ограничение по Брендам
        if($brandsIds = $this->builder->brands()->ids()) {
            $query->where('products.brandId', 'in', $brandsIds);
        }
        // Ограничение по Сезону
        if($seasonsIds = $this->builder->seasons()->ids()) {
            $query->where('products.seasonId', 'in', $seasonsIds);
        }
        // Ограничение по городу
        if($cityId = $this->builder->cities()->id()) {
            $this->join($query, 'allowOptions', 'productId', 'products.id');
            $this->join($query, 'restOptions', 'optionId', 'allowOptions.id');
            $this->join($query, 'shops', 'id', 'restOptions.shopId');
            $query->where('shops.cityId', $cityId);
        }
        // Ограничение по магазину
        if($shopId = $this->builder->shops()->id()) {
            $this->join($query, 'allowOptions', 'productId', 'products.id');
            $this->join($query, 'restOptions', 'optionId', 'allowOptions.id');
            $query->where('restOptions.shopId', $shopId);
        }
        // Ограничение по акции
        if($actionId = $this->builder->actions()->id()) {
            if($actionId == 'sale') {
                $this->join($query, 'allowOptions', 'productId', 'products.id');
                $query->where('allowOptions.special', 1);
                $query->where('allowOptions.old_price', '>', 'allowOptions.price');
            } else {
                $action = $this->builder->connection()->selectQuery()->table('actions')->where('id', $actionId)->execute()->current();
                if($action) {
                    switch($action->actionTypeId) {
                        case '53001';
                        case '53005';
                        case '53014';
                            $this->join($query, 'allowOptions', 'productId', 'products.id');
                            $this->join($query, 'actionProducts', 'optionId', 'allowOptions.id');
                            $query->where('actionProducts.actionId', $action->id);
                            break;
                        case '53006';
                        case '53007';
                        case '53008';
                        case '53009';
                        case '53010';
                            $this->join($query, 'allowOptions', 'productId', 'products.id');
                            switch($action->price_flag) {
                                case '0':
                                    $query->where('allowOptions.special', 0);
                                    break;
                                case '2':
                                    $query->where('allowOptions.special', 1);
                                    break;
                            }
                    }
                }
            }
        }
        // Ограничение по ценам От
        if($exclude !== 'prices' && $from = $this->builder->data()->get('prices.from')) {
            $this->join($query, 'allowOptions', 'productId', 'products.id');
            $query->where('allowOptions.price', '>=', str_replace(' ', '', $from));
        }
        // Ограничение по ценам До
        if($exclude !== 'prices' && $to = $this->builder->data()->get('prices.to')) {
            $this->join($query, 'allowOptions', 'productId', 'products.id');
            $query->where('allowOptions.price', '<=', str_replace(' ', '', $to));
        }
        if($exclude !== 'prices' && ($id = $this->builder->data()->get('prices.id')) !== null) {
            $this->join($query, 'allowOptions', 'productId', 'products.id');
            $this->join($query, 'productTypes', 'id', 'products.productTypeId');
            switch($id) {
                case 0:
                    $query->where('allowOptions.price', '<=', new Expression('productTypes.priceMin'));
                    break;
                case 1:
                    $query->where('allowOptions.price', '>=', new Expression('productTypes.priceMin'));
                    $query->where('allowOptions.price', '<=', new Expression('productTypes.priceMax'));
                    break;
                case 2:
                    $query->where('allowOptions.price', '>=', new Expression('productTypes.priceMax'));
                    break;
            }
        }
        if($exclude) {
            $typesIds = $this->builder->types()->ids();
            // Добавляем типы изделий, Если есть условия фильтрации в дополнительном фильтре
            if($extra = $this->builder->data()->get('extra')) {
                $typesIds = array_merge($typesIds, array_keys($extra));
            }
            // Добавляем тип изделия, Если есть условия фильтрации для конкретного типа
            if($typeId = $this->builder->data()->get('typeId')) {
                if(!in_array($typeId, $typesIds)) {
                    $typesIds[] = $typeId;
                }
            }
            // Ограничение по типам изделий
            if($typesIds) {
                // Добавление условий к основным
                $query->startAndWhereGroup();
                foreach($typesIds as $typeId) {
                    try {
                        // Получаем класс типа
                        $type = $this->builder->types()->get($typeId);
                        // Каждый тип в условии ИЛИ
                        $query->startOrWhereGroup();
                        // Фильтр по типу
                        $query->where('products.productTypeId', $typeId);
                        // Фильтр по размерам
                        if($exclude !== 'sizes' && $sizesIds = $type->sizes()->ids()) {
                            $this->join($query, 'allowOptions', 'productId', 'products.id');
                            $query->where('allowOptions.sizeId', 'in', $sizesIds);
                        }
                        if($exclude !== 'girths' && $girthsIds = $type->girths()->ids()) {
                            $this->join($query, 'allowOptions', 'productId', 'products.id');
                            $query->where('allowOptions.girthId', 'in', $girthsIds);
                        }
                        if($exclude !== 'colors' && $colorsIds = $type->colors()->ids()) {
                            $this->join($query, 'allowOptions', 'productId', 'products.id');
                            $this->join($query, 'optionsColors', 'optionId', 'allowOptions.id');
                            $query->where('optionsColors.colorId', 'in', $colorsIds);
                        }
                        if($valuesIds = $type->values()->ids()) {
                            $this->join($query, 'productsAttributeValues', 'productId', 'products.id');
                            $query->where('productsAttributeValues.attributeValueId', 'in', $valuesIds);
                        }
                        $query->endWhereGroup();
                    } catch(\Exception $e) {
                        $query->where(new Expression(1), 1);
                    }
                }
                $query->endWhereGroup();
            }
        }
    }
}
