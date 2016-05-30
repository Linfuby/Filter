<?php
namespace Meling\Filter\Lists\Fields\Types;

abstract class Extra
{
    public $id;

    public $name;

    public $checked;

    public $disabled;

    /**
     * Field constructor.
     * @param string $id
     * @param string $name
     * @param bool   $checked
     * @param bool   $disabled
     */
    public function __construct($id, $name, $checked, $disabled)
    {
        $this->id       = $id;
        $this->name     = $name;
        $this->checked  = $checked;
        $this->disabled = $disabled;
    }

    /**
     * @param null $bool
     * @return bool
     */
    public function checked($bool = null)
    {
        if($bool !== null) {
            $this->checked = $bool;
        }

        return $this->checked;
    }

    /**
     * @param null $bool
     * @return bool
     */
    public function disabled($bool = null)
    {
        if($bool !== null) {
            $this->disabled = $bool;
        }

        return $this->disabled;
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
