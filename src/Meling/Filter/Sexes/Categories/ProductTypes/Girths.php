<?php
namespace Meling\Filter\Sexes\Categories\ProductTypes;

/**
 * Class Girths
 * @package Meling\Filter\Sexes\Categories\ProductTypes
 * @since   2.0
 */
class Girths
{
    /** @var \Meling\Filter */
    protected $filter;

    /** @var \PHPixie\Slice\Type\ArrayData\Slice */
    protected $data;

    /** @var ProductType */
    protected $productType;

    /** @var Girths\Girth[] */
    private $girths;

    /**
     * Girths constructor.
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
     * @return Girths\Girth[]
     * @since 2.0
     */
    public function asArray()
    {
        $this->requireGirths();

        return $this->girths;
    }

    /**
     * @param mixed $id
     * @return Girths\Girth
     * @throws \Exception
     * @since 2.0
     */
    public function get($id)
    {
        $this->requireGirths();
        if(array_key_exists($id, $this->girths)) {
            return $this->girths[$id];
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
    private function requireGirths()
    {
        if($this->girths !== null) {
            return;
        }
        $query = $this->filter->query();
        $query->fields(
            array(
                'girths.id',
                'girths.name',
            )
        );
        $query->table('girths');
        $query->join('options');
        $query->on('options.girthId', 'girths.id');
        $query->join('products');
        $query->on('products.id', 'options.productId');
        $query->where('products.productTypeId', $this->productType->id());
        $query->orderAscendingBy('girths.name');
        $query->groupBy('girths.id');
        /** @var \PHPixie\Database\Result $result */
        $result = $query->execute();
        $girths = [];
        foreach($result->asArray() as $girth) {
            $productTypes[$girth->id] = new Girths\Girth($this->productType, $girth->id, $girth->name, in_array($girth->id, $this->selected()));
        }
        $this->girths = $girths;
    }

}

