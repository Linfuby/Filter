<?php
namespace Meling\Filter;

use PHPixie\Database\Type\SQL\Expression;

/**
 * Class Products
 * @package Meling\Filter
 * @since   2.0
 */
class Products
{
    /**
     * @var \Meling\Filter
     * @since 2.0
     */
    protected $filter;

    /**
     * @var array
     * @since 2.0
     */
    protected $selected = [];

    /**
     * Products constructor.
     * @param \Meling\Filter $filter
     * @since 2.0
     */
    public function __construct(\Meling\Filter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param string $withOut
     * @return array
     * @since 2.0
     */
    public function selected($withOut = null)
    {
        if($withOut === null) {
            $withOut = 'all';
        }
        if(!array_key_exists($withOut, $this->selected)) {
            $query = $this->filter->connection()->selectQuery();
            $query->fields('products.id');
            $query->table('products');
            // Только с остатками
            $query->join('restOptions', 'restProducts');
            $query->on('restProducts.productId', 'products.id');

            $query->join('options');
            $query->on('options.id', 'restProducts.optionId');
            // Только в доступных магазинах
            $query->join('shops');
            $query->on('shops.id', 'restProducts.shopId');
            $query->where('shops.publish', 1);
            $query->where('shops.active', 1);
            $query->where('shops.hidden', 0);
            // Только с изображениями
            $query->join('productImages');
            $query->on('productImages.productId', 'products.id');
            // Половая принадлежность
            if($this->filter->sexes()->selected() !== 3003) {
                $query->where('products.sexId', $this->filter->sexes()->selected());
            }
            if($withOut !== 'locations') {
                if($this->filter->locations()->shopId()) {
                    // В определенных магазинах
                    $query->where('restProducts.shopId', $this->filter->locations()->shopId());
                } elseif($this->filter->locations()->cityId()) {
                    // В определенных городах
                    $query->where('shops.cityId', $this->filter->locations()->cityId());
                }
            }
            if($withOut !== 'actions') {

            }
            $sex = $this->filter->sexes()->get();
            // Подключение дополнительных Джоинов
            if($sex->categories()->selected() || $sex->prices()->id()) {
                $query->join('productTypes');
                $query->on('productTypes.id', 'products.productTypeId');
            }
            // Фильтрация по брендам
            if($sex->brands()->selected()) {
                $query->where('brandId', 'in', $sex->brands()->selected());
            }
            // Фильтрация по сезонам
            if($sex->seasons()->selected()) {
                $query->where('seasonId', 'in', $sex->seasons()->selected());
            }
            // Фильтрация по цене
            if($sex->prices()->id() !== null) {
                switch($sex->prices()->id()) {
                    // Дорогие
                    case 1:
                        $query->where('options.price', '>=', new Expression('`productTypes`.`priceMax`'));
                        break;
                    // Средние
                    case 0:
                        $query->where('options.price', '>=', new Expression('`productTypes`.`priceMin`'));
                        $query->where('options.price', '<=', new Expression('`productTypes`.`priceMax`'));
                        break;
                    // Дешевые
                    case -1:
                        $query->where('options.price', '<=', new Expression('`productTypes`.`priceMin`'));
                        break;
                }
            }
            // Фильтрация по диапазону цен
            if($sex->prices()->from()) {
                // От
                $query->where('options.price', '>=', $sex->prices()->from());
            }
            if($sex->prices()->to()) {
                // До
                $query->where('options.price', '<=', $sex->prices()->to());
            }
            $joinColors     = false;
            $joinAttributes = false;
            if($sex->categories()->selected()) {
                $query->startWhereGroup();
                foreach($sex->categories()->asArray() as $category) {
                    if($category->isSelect()) {
                        if($category->productTypes()->selected()) {
                            foreach($category->productTypes()->asArray() as $productType) {
                                if($productType->isSelect()) {
                                    $query->startOrGroup();
                                    $query->where('productTypes.categoryId', $category->id());
                                    $query->where('productTypeId', $productType->id());
                                    if($productType->sizes()->selected()) {
                                        $query->where('options.sizeId', 'in', $productType->sizes()->selected());
                                    }
                                    if($productType->girths()->selected()) {
                                        $query->where('options.girthId', 'in', $productType->girths()->selected());
                                    }
                                    if($productType->colors()->selected()) {
                                        if(!$joinColors) {
                                            $query->join('optionsColors');
                                            $query->on('optionsColors.optionId', 'options.id');
                                            $joinColors = true;
                                        }
                                        $query->where('optionsColors.colorId', 'in', $productType->colors()->selected());
                                    }
                                    if($productType->attributes()->selected()) {
                                        if(!$joinAttributes) {
                                            $query->join('productsAttributeValues');
                                            $query->on('productsAttributeValues.productId', 'products.id');
                                            $joinAttributes = true;
                                        }
                                        $query->where('productsAttributeValues.attributeValueId', 'in', $productType->attributes()->selected());
                                    }
                                    $query->endGroup();
                                }
                            }
                        } else {
                            $query->where('productTypes.categoryId', $category->id());
                        }
                    }
                }
                $query->endWhereGroup();
            }
            $query->groupBy('products.id');
            /** @var \PHPixie\Database\Result $result */
            $result = $query->execute();
            $this->selected[$withOut] = $result->getField('id');
        }

        return $this->selected[$withOut];
    }

}

