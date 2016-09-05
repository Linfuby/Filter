<?php
namespace Meling\Filter\Sexes\Categories\ProductTypes;

/**
 * Class Sizes
 * @package Meling\Filter\Sexes\Categories\ProductTypes
 * @since   2.0
 */
class Sizes
{
    /** @var \Meling\Filter */
    protected $filter;

    /** @var \PHPixie\Slice\Type\ArrayData\Slice */
    protected $data;

    /** @var ProductType */
    protected $productType;

    /** @var Sizes\Size[] */
    private $sizes;

    /**
     * Sizes constructor.
     * @param \Meling\Filter                      $filter
     * @param \PHPixie\Slice\Type\ArrayData\Slice $data
     * @param ProductType                         $productType
     * @since 2.0
     */
    public function __construct(\Meling\Filter $filter, \PHPixie\Slice\Type\ArrayData\Slice $data, ProductType $productType)
    {
        $this->filter      = $filter;
        $this->data        = $data;
        $this->productType = $productType;
    }

    /**
     * @return Sizes\Size[]
     * @since 2.0
     */
    public function asArray()
    {
        $this->requireSizes();

        return $this->sizes;
    }

    /**
     * @param mixed $id
     * @return Sizes\Size
     * @throws \Exception
     * @since 2.0
     */
    public function get($id)
    {
        $this->requireSizes();
        if(array_key_exists($id, $this->sizes)) {
            return $this->sizes[$id];
        }
        throw new \Exception();
    }

    /**
     * @return array
     * @since 2.0
     */
    public function selected()
    {
        return $this->data->get(null, []);
    }

    /**
     * @since 2.0
     */
    private function requireSizes()
    {
        if($this->sizes !== null) {
            return;
        }
        $query = $this->filter->query();
        $query->fields(
            array(
                'sizes.id',
                'sizes.name',
            )
        );
        $query->table('sizes');
        $query->join('options');
        $query->on('options.sizeId', 'sizes.id');
        $query->join('products');
        $query->on('products.id', 'options.productId');
        $query->where('products.productTypeId', $this->productType->id());
        $query->orderAscendingBy('sizes.name');
        $query->groupBy('sizes.id');
        /** @var \PHPixie\Database\Result $result */
        $result = $query->execute();
        $sizes  = [];
        foreach($result->asArray() as $size) {
            $sizes[$size->id] = new Sizes\Size($this->productType, $size->id, $size->name, in_array($size->id, $this->selected()));
        }
        $this->sizes = $sizes;
    }

}

