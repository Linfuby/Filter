<?php
namespace Meling\Filter\Lists\Fields;

/**
 * Class Many
 * @package Meling\Filter\Lists
 */
abstract class Many
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
    protected $checked;

    /**
     * Many constructor.
     * @param string $id
     * @param string $name
     * @param bool   $checked
     */
    public function __construct($id, $name, $checked = false)
    {
        $this->id      = $id;
        $this->name    = $name;
        $this->checked = $checked;
    }

    /**
     * @return bool
     */
    public function checked()
    {
        return $this->checked;
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

}
