<?php
namespace Meling\Filter\Lists\Fields\Types\Extra\Values;

class Value extends \Meling\Filter\Lists\Fields\Types\Extra
{
    /**
     * @var \Meling\Filter\Lists\Fields\Types\Extra\Attributes\Attribute
     */
    private $attribute;

    /**
     * Value constructor.
     * @param string                                                       $id
     * @param string                                                       $name
     * @param bool                                                         $checked
     * @param bool                                                         $disabled
     * @param \Meling\Filter\Lists\Fields\Types\Extra\Attributes\Attribute $attribute
     */
    public function __construct($id, $name, $checked, $disabled, \Meling\Filter\Lists\Fields\Types\Extra\Attributes\Attribute $attribute)
    {
        parent::__construct($id, $name, $checked, $disabled);
        $this->attribute = $attribute;
    }

    public function attribute()
    {
        return $this->attribute;
    }

}
