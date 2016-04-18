<?php
namespace Meling\Filter\Lists;

/**
 * Список Типов изделий
 * Class Types
 * @package Meling\Filter\Lists
 */
class Types
{
    /**
     * @var \PHPixie\Database\Connection
     */
    protected $db;

    /**
     * Выбранные пункты фильтрации
     * @var array
     */
    protected $ids;

    /**
     * Выбранные пункты фильтрации (Категории)
     * @var array
     */
    protected $categoryIds;

    /**
     * @var object[]
     */
    protected $items;

    /**
     * Types constructor.
     * @param \PHPixie\Database\Connection $db
     * @param array                        $ids
     * @param array                        $categoryIds
     */
    public function __construct(\PHPixie\Database\Connection $db, array $ids, array $categoryIds)
    {
        $this->db          = $db;
        $this->ids         = $ids;
        $this->categoryIds = $categoryIds;
    }


    /**
     * @return \object[]
     */
    public function asArray()
    {
        $this->requireItems();

        return $this->items;
    }

    /**
     * @param $id
     * @return object
     */
    public function get($id)
    {
        $this->requireItems();
        if(array_key_exists($id, $this->items)) {
            return $this->items[$id];
        }

        return $this->items[0];
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->ids;
    }

    private function requireItems()
    {
        if($this->items !== null) {
            return;
        }
        $items = array();
        $query = $this->db->selectQuery();
        $query->fields()
        $query->table('productTypes', 'pt');
        foreach($query->execute() as $item) {
            if(in_array($item->id, $this->id())) {
                $item->checked = 1;
            } else {
                $item->checked = 0;
            }
            $items[$item->id] = $item;
        }
        $this->items = $items;
    }

}
