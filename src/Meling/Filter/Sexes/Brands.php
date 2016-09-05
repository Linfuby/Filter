<?php
namespace Meling\Filter\Sexes;

/**
 * Class Brands
 * @package Meling\Filter\Sexes
 * @since   2.0
 */
class Brands
{
    /**
     * @var \Meling\Filter
     * @since 2.0
     */
    protected $filter;

    /**
     * @var Sex
     * @since 2.0
     */
    protected $sex;

    /**
     * @var array
     * @since 2.0
     */
    protected $selected;

    /**
     * @var Brands\Brand[]
     * @since 2.0
     */
    private $brands;

    /**
     * Brands constructor.
     * @param \Meling\Filter $filter
     * @param Sex            $sex
     * @param array          $selected
     * @since    2.0
     */
    public function __construct(\Meling\Filter $filter, Sex $sex, $selected = [])
    {
        $this->filter   = $filter;
        $this->selected = $selected;
        $this->sex      = $sex;
    }

    /**
     * @return Brands\Brand[]
     * @since 2.0
     */
    public function asArray()
    {
        $this->requireBrands();

        return $this->brands;
    }

    /**
     * @param mixed $id
     * @return Brands\Brand
     * @throws \Exception
     * @since 2.0
     */
    public function get($id)
    {
        $this->requireBrands();
        if(array_key_exists($id, $this->brands)) {
            return $this->brands[$id];
        }
        throw new \Exception('Brand ' . $id . ' does not exist');
    }

    /**
     * @return array
     * @since 2.0
     */
    public function selected()
    {
        return $this->selected;
    }

    protected function requireBrands()
    {
        if($this->brands !== null) {
            return;
        }
        $query = $this->filter->connection()->selectQuery();
        $query->fields(
            array(
                'brands.id',
                'brands.name',
                'products.sexId',
            )
        );
        $query->table('brands');
        $query->join('products');
        $query->on('products.brandId', 'brands.id');
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
        // Только с половой принадлежностью
        if($this->sex->id() !== 3003) {
            $query->where('products.sexId', $this->sex->id());
        }
        // Только опубликованные бренды
        $query->where('brands.publish', 1);
        $query->orderAscendingBy('brands.name');
        $query->groupBy('brands.id');
        /** @var \PHPixie\Database\Result $result */
        $result = $query->execute();
        $brands = [];
        foreach($result->asArray() as $brand) {
            $brands[$brand->id] = new Brands\Brand($brand->id, $brand->name, in_array($brand->id, $this->selected()));
        }
        $this->brands = $brands;
    }

}

