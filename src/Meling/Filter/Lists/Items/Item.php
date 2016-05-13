<?php
namespace Meling\Filter\Lists\Items;

class Item
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $selected;

    /**
     * Item constructor.
     * @param mixed  $id
     * @param string $name
     * @param bool   $selected
     */
    public function __construct($id, $name, $selected)
    {
        $this->id       = $id;
        $this->name     = $name;
        $this->selected = $selected;
    }

}
