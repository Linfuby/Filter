<?php
namespace Meling\Filter\Sexes\Categories\ProductTypes;

/**
 * Class Attributes
 * @package Meling\Filter\Sexes\Categories\ProductTypes
 * @since   2.0
 */
class Attributes
{
    /** @var \Meling\Filter */
    protected $filter;

    /** @var \PHPixie\Slice\Type\ArrayData\Slice */
    protected $data;

    /** @var ProductType */
    protected $productType;

    /** @var Attributes\Attribute[] */
    private $attributes;

    /**
     * Attributes constructor.
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
     * @return Attributes\Attribute[]
     * @since 2.0
     */
    public function asArray()
    {
        $this->requireAttributes();

        return $this->attributes;
    }

    /**
     * @param mixed $id
     * @return Attributes\Attribute
     * @throws \Exception
     * @since 2.0
     */
    public function get($id)
    {
        $this->requireAttributes();
        if(array_key_exists($id, $this->attributes)) {
            return $this->attributes[$id];
        }
        throw new \Exception();
    }

    public function selected()
    {
        $selected = [];
        foreach($this->data->keys() as $attributeId) {
            $selected = array_merge($selected, $this->data->get($attributeId, []));
        }

        return $selected;
    }

    /**
     * @since 2.0
     */
    private function requireAttributes()
    {
        if($this->attributes !== null) {
            return;
        }
        $query = $this->filter->query();
        $query->fields(
            array(
                'attributeValues.id',
                'attributeValues.name',
                'attributeId'   => 'attributes.id',
                'attributeName' => 'attributes.name',
            )
        );
        $query->table('attributeValues');
        $query->join('attributes');
        $query->on('attributes.id', 'attributeValues.attributeId');
        $query->where('attributes.productTypeId', $this->productType->id());

        $query->orderAscendingBy('attributes.name');
        $query->orderAscendingBy('attributeValues.name');
        $query->groupBy('attributeValues.id');
        /** @var \PHPixie\Database\Result $result */
        $result     = $query->execute();
        $attributes = [];
        foreach($result->asArray() as $attribute) {
            if(!array_key_exists($attribute->attributeName, $attributes)) {
                $attributes[$attribute->attributeName] = array(
                    'id'              => $attribute->attributeId,
                    'name'            => $attribute->attributeName,
                    'attributeValues' => [],
                );
            }
            $attributes[$attribute->attributeName]['attributeValues'][] = new Attributes\AttributeValue($attribute->id, $attribute->name, in_array($attribute->id, $this->data->get($attribute->attributeId, [])));
        }
        $this->attributes = array();
        foreach($attributes as $attribute) {
            \PHPixie\Debug::log($attribute);
            $this->attributes[$attribute['id']] = new Attributes\Attribute($this->productType, $attribute['id'], $attribute['name'], $attribute['attributeValues'], $this->data->keys($attribute['id'], []));
        }
    }

}

