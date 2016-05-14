<?php
namespace Meling\Filter\Lists;

abstract class Implementation
{
    /**
     * @var \Meling\Filter\Builder
     */
    protected $builder;

    /**
     * @var \PHPixie\Slice\Type\ArrayData\Editable
     */
    protected $data;

    /**
     * @var Items\Item[]
     */
    protected $items;

    /**
     * Builder constructor.
     *
     * @param \Meling\Filter\Builder              $builder
     * @param \PHPixie\Slice\Type\ArrayData\Slice $data
     */
    public function __construct(\Meling\Filter\Builder $builder, \PHPixie\Slice\Type\ArrayData\Slice $data)
    {
        $this->builder = $builder;
        $this->data    = $data;
    }

    /**
     * @return bool
     */
    public function active()
    {
        return (bool)$this->id();
    }

    /**
     * @return Items\Item[]
     */
    public function asArray()
    {
        $this->requireItems();

        return $this->items;
    }

    /**
     * @param $id
     *
     * @return Items\Item
     * @throws \Exception
     */
    public function get($id)
    {
        $this->requireItems();
        if (array_key_exists($id, $this->items)) {
            return $this->items[$id];
        }
        throw new \Exception('Item ' . $id . ' does not exist');
    }

    /**
     * @return array|string
     */
    public function id()
    {
        return $this->data->get();
    }

    protected function buildItem($id, $name, $selected = false)
    {
        return new Items\Item($id, $name, $selected);
    }

    /**
     * @return Items\Item[]
     */
    protected abstract function generateItems();

    protected function requireItems()
    {
        if ($this->items !== null) {
            return;
        }
        $this->items = $this->generateItems();
    }

}
