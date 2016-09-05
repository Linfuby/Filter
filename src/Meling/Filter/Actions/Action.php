<?php
namespace Meling\Filter\Actions;

/**
 * Class Action
 * @package Meling\Filter\Actions
 * @since   2.0
 */
class Action
{
    /** @var mixed */
    protected $id;

    /** @var string */
    protected $name;

    /** @var bool */
    protected $isSelect;

    /**
     * Action constructor.
     * @param mixed  $id
     * @param string $name
     * @param bool   $isSelect
     * @since 2.0
     */
    public function __construct($id, $name, $isSelect = false)
    {
        $this->id       = $id;
        $this->name     = $name;
        $this->isSelect = $isSelect;
    }

    /**
     * @return mixed
     * @since 2.0
     */
    public function id()
    {
        return (int)$this->id;
    }

    /**
     * @return bool
     * @since 2.0
     */
    public function isSelect()
    {
        return (bool)$this->isSelect;
    }

    /**
     * @return string
     * @since 2.0
     */
    public function name()
    {
        return $this->name;
    }

}

