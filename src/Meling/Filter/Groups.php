<?php
namespace Meling\Filter;

class Groups
{
    /**
     * @var Builder
     */
    protected $builder;
    /**
     * @var \PHPixie\Slice\Type\ArrayData\Slice
     */
    protected $data;
    /**
     * @var Groups\Group[]
     */
    protected $groups;
    /**
     * @var Lists\Items\Type
     */
    protected $type;

    /**
     * Extra constructor.
     *
     * @param Builder                             $builder
     * @param Lists\Items\Type                    $type
     * @param \PHPixie\Slice\Type\ArrayData\Slice $data
     */
    public function __construct(Builder $builder, Lists\Items\Type $type, \PHPixie\Slice\Type\ArrayData\Slice $data)
    {
        $this->builder = $builder;
        $this->type    = $type;
        $this->data    = $data;
    }

    public function active()
    {
        return (bool)$this->data->get();
    }

    /**
     * @return Groups\Group[]
     */
    public function asArray()
    {
        $this->requireGroups();

        return $this->groups;
    }

    /**
     * @param $id
     *
     * @return Groups\Group
     * @throws \Exception
     */
    public function get($id)
    {
        $this->requireGroups();
        if (array_key_exists($id, $this->groups)) {
            return $this->groups[$id];
        }
        throw new \Exception('Group ' . $id . ' does not exist');
    }

    protected function buildAttributeGroup($id, $name, $data)
    {
        return new Groups\Attribute($this->builder, $this->builder->firstQuery(), $id, $name, $data);
    }

    protected function buildColorGroup($id, $name, $data)
    {
        return new Groups\Color($this->builder, $this->builder->firstQuery(), $id, $name, $data);
    }

    protected function buildGirthGroup($id, $name, $data)
    {
        return new Groups\Girth($this->builder, $this->builder->firstQuery(), $id, $name, $data);
    }

    protected function buildSizesGroup($id, $name, $data)
    {
        return new Groups\Size($this->builder, $this->builder->firstQuery(), $id, $name, $data);
    }

    protected function requireGroups()
    {
        if ($this->groups !== null) {
            return;
        }
        $groups     = array(
            'sizes'  => $this->buildSizesGroup('sizes', 'Размеры', $this->data->get('sizes', array())),
            'girths' => $this->buildGirthGroup('girths', 'Чашка', $this->data->get('girths', array())),
            'colors' => $this->buildColorGroup('colors', 'Цвета', $this->data->get('colors', array())),
        );
        $attributes = array();
        foreach ($attributes as $attribute) {
            $groups[(string)$attribute->id] = $this->buildAttributeGroup($attribute->id, $attribute->name,
                $this->data->get($attribute->id, array()));
        }
        $this->groups = $groups;
    }

}
