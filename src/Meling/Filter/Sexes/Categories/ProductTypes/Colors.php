<?php
namespace Meling\Filter\Sexes\Categories\ProductTypes;

/**
 * Class Colors
 * @package Meling\Filter\Sexes
 * @since   2.0
 */
class Colors
{
    /** @var \Meling\Filter */
    protected $filter;

    /** @var \PHPixie\Slice\Type\ArrayData\Slice */
    protected $data;

    /** @var ProductType */
    protected $productType;

    /** @var Colors\Color[] */
    private $colors;

    /**
     * Colors constructor.
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
     * @return Colors\Color[]
     * @since 2.0
     */
    public function asArray()
    {
        $this->requireColors();

        return $this->colors;
    }

    /**
     * @param mixed $id
     * @return Colors\Color
     * @throws \Exception
     * @since 2.0
     */
    public function get($id)
    {
        $this->requireColors();
        if(array_key_exists($id, $this->colors)) {
            return $this->colors[$id];
        }
        throw new \Exception('Color ' . $id . ' does not exist');
    }

    /**
     * @return ProductType
     * @since 2.0
     */
    public function productType()
    {
        return $this->productType;
    }

    /**
     * @return array
     * @since 2.0
     */
    public function selected()
    {
        return $this->data->get(null, []);
    }

    protected function requireColors()
    {
        if($this->colors !== null) {
            return;
        }
        $query = $this->filter->query();
        $query->fields(
            array(
                'colors.id',
                'colors.name',
            )
        );
        $query->table('colors');
        $query->join('optionsColors');
        $query->on('optionsColors.colorId', 'colors.id');
        $query->join('options');
        $query->on('options.id', 'optionsColors.optionId');
        $query->join('products');
        $query->on('products.id', 'options.productId');
        $query->where('products.productTypeId', $this->productType->id());
        $query->orderAscendingBy('colors.name');
        $query->groupBy('colors.id');
        /** @var \PHPixie\Database\Result $result */
        $result = $query->execute();
        $colors = [];
        foreach($result->asArray() as $color) {
            $colors[$color->id] = new Colors\Color($this->productType, $color->id, $color->name, in_array($color->id, $this->selected()));
        }
        $this->colors = $colors;
    }

}

