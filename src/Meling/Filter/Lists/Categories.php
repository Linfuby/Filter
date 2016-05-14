<?php
namespace Meling\Filter\Lists;

class Categories extends Implementation implements Lists
{
    /**
     * @return Items\Item[]
     */
    protected function generateItems()
    {
        $query = $this->builder->connection()->selectQuery();
        $query->table('categories');
        $query->where('depth', 1);
        $query->orderAscendingBy('name');
        if($this->builder->sexes()->active()) {
            $query->where('sexId', $this->builder->sexes()->id());
        }
        $items = array();
        foreach($query->execute() as $item) {
            $items[(string)$item->id] = $this->buildItem((string)$item->id, $item->name,
                in_array((string)$item->id, $this->id()));
        }

        return $items;
    }

}
