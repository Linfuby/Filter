<?php
namespace Meling\Filter\Lists\Fields\Types\Extra;

abstract class ExtraList
{
    /**
     * @var \Meling\Filter\Lists\Fields\Types\Type
     */
    protected $type;

    /**
     * @var \Meling\Filter\Lists\Fields\Types\Extra[]
     */
    protected $items;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var string
     */
    protected $name;

    /**
     * Sizes constructor.
     * @param \Meling\Filter\Lists\Fields\Types\Type $type
     * @param string                                 $name
     * @param array                                  $data
     */
    public function __construct(\Meling\Filter\Lists\Fields\Types\Type $type, $name, $data)
    {
        $this->type = $type;
        $this->name = $name;
        $this->data = $data ? $data : array();
    }

    public function asArray()
    {
        $this->requireItems();

        return $this->items;
    }

    public function get($id)
    {
        $this->requireItems();
        if(array_key_exists($id, $this->items)) {
            return $this->items[$id];
        }

        throw new \PHPixie\Auth\Exception("Size '$id' does not exist");
    }

    public function ids()
    {
        return $this->data;
    }

    protected function requireItems()
    {
        if($this->items !== null) {
            return;
        }
        /** @var \Meling\Filter\Lists\Fields\Types\Extra[] $items */
        $items = array();
        foreach($this->query()->execute() as $item) {
            $items[$item->id] = $this->buildItem($item, in_array($item->id, $this->ids()), true);
        }
        $productItems = $this->query($this->name)->execute()->getField('id');
        foreach($items as $item) {
            if(in_array((string)$item->id, $productItems)) {
                $item->disabled(false);
            }
        }

        $this->items = $items;
    }

    /**
     * @param object $item
     * @param bool   $checked
     * @param bool   $disabled
     * @return \Meling\Filter\Lists\Fields\Types\Extra
     */
    protected abstract function buildItem($item, $checked, $disabled);

    /**
     * @param null $exclude
     * @return \PHPixie\Database\Driver\PDO\Query\Type\Select
     */
    protected abstract function query($exclude = null);

}