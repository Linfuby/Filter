<?php
namespace Meling\Filter\Lists;

/**
 * Class Types
 * @method Items\Type[] asArray();
 * @method Items\Type get($id);
 * @package Meling\Filter\Lists
 */
class Types extends Implementation implements Lists
{
    /**
     * @return Items\Item[]
     */
    protected function generateItems()
    {
        $query = $this->builder->connection()->selectQuery();
        $query->table('productTypes');
        $query->orderAscendingBy('name');
        if ($this->builder->categories()->active()) {
            $query->where('categoryId', 'in', $this->builder->categories()->id());
        }
        $items = array();
        foreach ($query->execute() as $item) {
            $category                 = $this->builder->categories()->get($item->categoryId);
            $items[(string)$item->id] = new Items\Type((string)$item->id, $item->name,
                in_array((string)$item->id, $this->id()), $category);
        }

        return $items;
    }


}
