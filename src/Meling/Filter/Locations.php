<?php
namespace Meling\Filter;

/**
 * Class Locations
 * Список Локаций (Город, Магазин, Весь Ассортимент)
 * @package Meling\Filter
 * @since   2.0
 */
class Locations
{
    /**
     * Фильтр
     * @var \Meling\Filter
     * @since 2.0
     */
    protected $filter;

    /**
     * Выбранные локации
     * @var \PHPixie\Slice\Type\ArrayData\Slice
     * @since 2.0
     */
    protected $data;

    /**
     * Все локации
     * @var Locations\City[]
     * @since 2.0
     */
    private $cities;

    /**
     * Locations constructor.
     * @param \Meling\Filter                      $filter Фильтр
     * @param \PHPixie\Slice\Type\ArrayData\Slice $data   Выделенные локации
     * @since    2.0
     */
    public function __construct(\Meling\Filter $filter, \PHPixie\Slice\Type\ArrayData\Slice $data)
    {
        $this->filter = $filter;
        $this->data   = $data;
    }

    /**
     * Все локации
     * @return Locations\City[]
     * @since 2.0
     */
    public function asArray()
    {
        $this->requireCities();

        return $this->cities;
    }

    /**
     * Выбранный город
     * @return mixed
     * @since 2.0
     */
    public function cityId()
    {
        return $this->data->get('cityId');
    }

    /**
     * Город
     * @param mixed $id
     * @return Locations\City
     * @throws \Exception
     * @since 2.0
     */
    public function get($id = null)
    {
        $this->requireCities();
        if($id === null) {
            $id = $this->cityId();
        }
        if(array_key_exists($id, $this->cities)) {
            return $this->cities[$id];
        }
        throw new \Exception();
    }

    /**
     * Выбранный Магазин
     * @return mixed
     * @since 2.0
     */
    public function shopId()
    {
        return $this->data->get('shopId');
    }

    /**
     * Все локации
     * @since 2.0
     */
    protected function requireCities()
    {
        if($this->cities !== null) {
            return;
        }
        $query = $this->filter->connection()->selectQuery();
        $query->fields(
            array(
                'shops.id',
                'shops.name',
                'shops.street',
                'shops.phone',
                'shops.work_times',
                'shops.lat',
                'shops.lng',
                'cityId'   => 'cities.id',
                'cityName' => 'cities.name',
            )
        );
        $query->table('shops');
        $query->join('cities');
        $query->on('cities.id', 'shops.cityId');
        $query->where('shops.publish', 1);
        $query->where('shops.active', 1);
        $query->where('shops.hidden', 0);
        $query->orderAscendingBy('cities.name');
        $query->orderAscendingBy('shops.name');
        $query->groupBy('shops.id');
        /** @var \PHPixie\Database\Result $result */
        $result  = $query->execute();
        $sources = [];
        $city    = null;
        foreach($result->asArray() as $shop) {
            if(!array_key_exists($shop->cityName, $sources)) {
                $sources[$shop->cityName] = array(
                    'id'       => $shop->cityId,
                    'name'     => $shop->cityName,
                    'isSelect' => $shop->cityId == $this->cityId() || $shop->id == $this->shopId(),
                    'shops'    => [],
                );
            }
            $sources[$shop->cityName]['shops'][] = new Locations\Shop($shop->id, $shop->name, $shop->street, $shop->phone, $shop->work_times, $shop->lat, $shop->lng, $shop->id == $this->shopId());
        }
        $cities = [];
        foreach($sources as $city) {
            if(!array_key_exists($city['id'], $cities)) {
                $cities[$city['id']] = new Locations\City($this->filter, $city['id'], $city['name'], $city['shops']);
            }
        }
        $this->cities = $cities;
    }

}
