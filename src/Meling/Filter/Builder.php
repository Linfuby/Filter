<?php
namespace Meling\Filter;

class Builder
{
    /**
     * @var \PHPixie\Database\Connection
     */
    protected $connection;

    /**
     * @var \PHPixie\Slice\Type\ArrayData\Editable
     */
    protected $data;

    /**
     * @var array
     */
    protected $instances = array();

    /**
     * Builder constructor.
     * @param \PHPixie\Database\Connection           $connection
     * @param \PHPixie\Slice\Type\ArrayData\Editable $data
     */
    public function __construct(\PHPixie\Database\Connection $connection, \PHPixie\Slice\Type\ArrayData\Editable $data)
    {
        $this->connection = $connection;
        if($typeIds = $data->get('types')) {
            foreach($data->keys('extra') as $typeId) {
                if(!in_array($typeId, $typeIds)) {
                    $data->remove('extra.' . $typeId);
                }
            }
        }

        $this->data = $data;
    }

    /**
     * @return Lists\Fields\Actions
     */
    public function actions()
    {
        return $this->instance('actions');
    }

    /**
     * @return Lists\Fields\Brands
     */
    public function brands()
    {
        return $this->instance('brands');
    }

    /**
     * @return Lists\Fields\Categories
     */
    public function categories()
    {
        return $this->instance('categories');
    }

    /**
     * @return Lists\Fields\Cities
     */
    public function cities()
    {
        return $this->instance('cities');
    }

    /**
     * @return \PHPixie\Database\Connection
     */
    public function connection()
    {
        return $this->connection;
    }

    /**
     * @return \PHPixie\Slice\Type\ArrayData\Editable
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return Lists\Fields\Prices
     */
    public function prices()
    {
        return $this->instance('prices');
    }

    /**
     * @return Products
     */
    public function products()
    {
        return $this->instance('products');
    }

    /**
     * @return Lists\Fields\Seasons
     */
    public function seasons()
    {
        return $this->instance('seasons');
    }

    /**
     * @return Lists\Fields\Sexes
     */
    public function sexes()
    {
        return $this->instance('sexes');
    }

    /**
     * @return Lists\Fields\Shops
     */
    public function shops()
    {
        return $this->instance('shops');
    }

    /**
     * @return Lists\Fields\Types
     */
    public function types()
    {
        return $this->instance('types');
    }

    protected function buildActions()
    {
        return new Lists\Fields\Actions($this, 'actions', $this->data()->get('actions'));
    }

    protected function buildBrands()
    {
        return new Lists\Fields\Brands($this, 'brands', $this->data()->get('brands', array()));
    }

    protected function buildCategories()
    {
        return new Lists\Fields\Categories($this, 'categories', $this->data()->get('categories', array()));
    }

    protected function buildCities()
    {
        return new Lists\Fields\Cities($this, 'cities', $this->data()->get('cities'));
    }

    protected function buildPrices()
    {
        return new Lists\Fields\Prices($this, 'prices', $this->data()->get('prices.from'), $this->data()->get('prices.to'), $this->data()->get('prices.id'));
    }

    protected function buildProducts()
    {
        return new Products($this);
    }

    protected function buildSeasons()
    {
        return new Lists\Fields\Seasons($this, 'seasons', $this->data()->get('seasons', array()));
    }

    protected function buildSexes()
    {
        return new Lists\Fields\Sexes($this, 'sexes', $this->data()->get('sexes'));
    }

    protected function buildShops()
    {
        return new Lists\Fields\Shops($this, 'shops', $this->data()->get('shops'));
    }

    protected function buildTypes()
    {
        return new Lists\Fields\Types($this, 'types', $this->data()->get('types', array()), $this->data()->arraySlice('extra'));
    }

    protected function instance($name)
    {
        if(!array_key_exists($name, $this->instances)) {
            $method                 = 'build' . ucfirst($name);
            $this->instances[$name] = $this->$method();
        }

        return $this->instances[$name];
    }

}
