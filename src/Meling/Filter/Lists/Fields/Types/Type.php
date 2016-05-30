<?php
namespace Meling\Filter\Lists\Fields\Types;

class Type extends \Meling\Filter\Lists\Fields\Many
{
    /**
     * @var array
     */
    protected $instances = array();

    /**
     * @var \Meling\Filter\Builder
     */
    protected $builder;

    /**
     * @var \Meling\Filter\Lists\Fields\Categories\Category
     */
    protected $category;

    /**
     * @var \PHPixie\Slice\Type\ArrayData\Slice
     */
    private $extraIds;

    /**
     * Many constructor.
     * @param string                                          $id
     * @param string                                          $name
     * @param bool                                            $checked
     * @param \Meling\Filter\Builder                          $builder
     * @param \Meling\Filter\Lists\Fields\Categories\Category $category
     * @param \PHPixie\Slice\Type\ArrayData\Slice             $extraIds
     */
    public function __construct($id, $name, $checked, \Meling\Filter\Builder $builder, \Meling\Filter\Lists\Fields\Categories\Category $category, \PHPixie\Slice\Type\ArrayData\Slice $extraIds)
    {
        parent::__construct($id, $name, $checked);
        $this->builder  = $builder;
        $this->category = $category;
        $this->extraIds = $extraIds;
    }

    /**
     * @return Extra\Attributes
     */
    public function attributes()
    {
        return $this->instance('attributes');
    }

    /**
     * @return \Meling\Filter\Builder
     */
    public function builder()
    {
        return $this->builder;
    }

    /**
     * @return \Meling\Filter\Lists\Fields\Categories\Category
     */
    public function category()
    {
        return $this->category;
    }

    /**
     * @return Extra\Colors
     */
    public function colors()
    {
        return $this->instance('colors');
    }

    /**
     * @return Extra\Girths
     */
    public function girths()
    {
        return $this->instance('girths');
    }

    /**
     * @return Extra\Sizes
     */
    public function sizes()
    {
        return $this->instance('sizes');
    }

    /**
     * @return Extra\Values
     */
    public function values()
    {
        return $this->instance('values');
    }

    protected function buildAttributes()
    {
        return new Extra\Attributes($this, 'attributes', $this->extraIds->arraySlice($this->id()));
    }

    protected function buildColors()
    {
        return new Extra\Colors($this, 'colors', $this->extraIds->get($this->id() . '.colors', array()));
    }

    protected function buildGirths()
    {
        return new Extra\Girths($this, 'girths', $this->extraIds->get($this->id() . '.girths', array()));
    }

    protected function buildSizes()
    {
        return new Extra\Sizes($this, 'sizes', $this->extraIds->get($this->id() . '.sizes', array()));
    }

    protected function buildValues()
    {
        return new Extra\Values($this, 'values', $this->extraIds->arraySlice($this->id()));
    }

    protected function instance($name)
    {
        if(!array_key_exists($name, $this->instances)) {
            $method                 = 'build' . ucfirst($name);
            $this->instances[$name] = $this->$method();
        }

        return $this->instances[$name];
    }

}
