<?php
namespace Meling\Filter\Sexes\Categories;

/**
 * Class ProductTypes
 * @package Meling\Filter\Sexes\Categories
 * @since   2.0
 */
class ProductTypes
{
    /** @var \Meling\Filter */
    protected $filter;

    /** @var Category */
    protected $category;

    /** @var \PHPixie\Slice\Type\ArrayData\Slice */
    protected $data;

    /** @var ProductTypes\ProductType[] */
    private $productTypes;

    /**
     * ProductTypes constructor.
     * @param \Meling\Filter                      $filter
     * @param Category                            $category
     * @param \PHPixie\Slice\Type\ArrayData\Slice $data
     * @since 2.0
     */
    public function __construct(\Meling\Filter $filter, Category $category, \PHPixie\Slice\Type\ArrayData\Slice $data)
    {
        $this->filter   = $filter;
        $this->category = $category;
        $this->data     = $data;
    }

    /**
     * @return ProductTypes\ProductType[]
     * @since 2.0
     */
    public function asArray()
    {
        $this->requireProductTypes();

        return $this->productTypes;
    }

    /**
     * @param mixed $id
     * @return ProductTypes\ProductType
     * @throws \Exception
     * @since 2.0
     */
    public function get($id)
    {
        $this->requireProductTypes();
        if(array_key_exists($id, $this->productTypes)) {
            return $this->productTypes[$id];
        }
        throw new \Exception();
    }

    /**
     * @return array
     * @since 2.0
     */
    public function selected()
    {
        return $this->data->keys();
    }

    /**
     * @since 2.0
     */
    protected function requireProductTypes()
    {
        if($this->productTypes !== null) {
            return;
        }
        $query = $this->filter->connection()->selectQuery();
        $query->fields(
            array(
                'productTypes.id',
                'productTypes.name',
            )
        );
        $query->table('productTypes');
        $query->where('productTypes.categoryId', $this->category->id());
        $query->orderAscendingBy('productTypes.name');
        $query->groupBy('productTypes.id');
        /** @var \PHPixie\Database\Result $result */
        $result       = $query->execute();
        $productTypes = [];
        foreach($result->asArray() as $productType) {
            $productTypes[$productType->id] = new ProductTypes\ProductType($this->filter, $this->data->arraySlice($productType->id), $this->category, $productType->id, $productType->name, in_array($productType->id, $this->selected()));
        }
        $this->productTypes = $productTypes;
    }
}

