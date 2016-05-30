<?php
namespace Meling\Filter\Lists\Fields;

abstract class One
{
    /**
     * @var string
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
     * One constructor.
     * @param string $id
     * @param string $name
     * @param bool   $selected
     */
    public function __construct($id, $name, $selected = false)
    {
        $this->id       = $id;
        $this->name     = $name;
        $this->selected = $selected;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function selected()
    {
        return $this->selected;
    }

}
