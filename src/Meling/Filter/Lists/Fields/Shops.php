<?php
namespace Meling\Filter\Lists\Fields;

use PHPixie\Database\Type\SQL\Expression;

/**
 * Список Магазинов
 * Class Shops
 * @method Shops\Shop[] asArray
 * @method Shops\Shop get($id)
 * @package Meling\Filter\Lists\Fields
 */
class Shops extends \Meling\Filter\Lists\FieldsOne
{
    public function cities()
    {
        /** @var \Meling\Filter\Lists\Fields\Cities\City[] $cities */
        $cities = array();
        foreach($this->asArray() as $shop) {
            if(!array_key_exists($shop->city()->id(), $cities)) {
                $cities[$shop->city()->name()] = $shop->city();
            }
            $cities[$shop->city()->name()]->addValue($shop);
        }

        return $cities;
    }

    protected function buildItem($item, $selected)
    {
        $city = $this->builder->cities()->get($item->cityId);

        return new \Meling\Filter\Lists\Fields\Shops\Shop($item->id, $item->name, $item->address, $item->phone, $item->times, $item->lat, $item->lng, $selected, $city);
    }

    /**
     * @return \PHPixie\Database\Driver\PDO\Query\Type\Select
     */
    protected function query()
    {
        if($this->query === null) {
            $this->query = $this->builder->connection()->selectQuery();
            // Идентфиикатор
            $this->query->fields(new Expression('DISTINCT(`shops`.`id`)'));
            // Название
            $this->query->fields('shops.name');
            $this->query->fields('shops.cityId');
            $this->query->fields(array('address' => 'shops.street'));
            $this->query->fields(array('phone' => 'shops.phone'));
            $this->query->fields(array('times' => 'shops.work_times'));
            $this->query->fields('shops.lat');
            $this->query->fields('shops.lng');
            // Таблица
            $this->query->table('shops');
            $this->query->where('shops.publish', 1);
            $this->query->where('shops.active', 1);
            $this->query->where('shops.hidden', 0);
            // Сортировка
            $this->query->orderAscendingBy('shops.cityId');
            $this->query->orderAscendingBy('shops.name');
        }

        return $this->query;
    }

}
