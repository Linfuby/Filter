<?php
namespace Meling\Filter\Lists\Fields\Types\Extra\Attributes;

class Attribute extends \Meling\Filter\Lists\Fields\Types\Extra
{
    /**
     * @var \Meling\Filter\Lists\Fields\Types\Extra\Values\Value[]
     */
    protected $values = array();

    /**
     * @param \Meling\Filter\Lists\Fields\Types\Extra\Values\Value $value
     */
    public function addValue($value)
    {
        $this->values[$value->id()] = $value;
    }

    /**
     * @return \Meling\Filter\Lists\Fields\Types\Extra\Values\Value[]
     */
    public function values()
    {
        return $this->values;
    }

}
