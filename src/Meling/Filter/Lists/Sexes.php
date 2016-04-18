<?php
namespace Meling\Filter\Lists;

/**
 * Список половой принадлежности
 * Class Sexes
 * @package Meling\Filter\Lists
 */
class Sexes
{
    /**
     * Выбранный пункт фильтрации
     * @var mixed
     */
    protected $sexId;

    /**
     * Все пункты фильтрации
     * @var object[]
     */
    protected $items;

    /**
     * Sexes constructor.
     * @param mixed $sexId
     */
    public function __construct($sexId = null)
    {
        $this->sexId = $sexId;
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
        return $this->sexId;
    }

    private function requireItems()
    {
        if($this->items !== null) {
            return;
        }
        $this->items = array(
            0    => (object)array(
                'id'      => 0,
                'name'    => 'Весь ассортимент',
                'checked' => $this->id() ? 0 : 1,
            ),
            3001 => (object)array(
                'id'      => 3001,
                'name'    => 'Женское',
                'checked' => $this->id() == 3001 ? 1 : 0,
            ),
            3002 => (object)array(
                'id'      => 3002,
                'name'    => 'Мужское',
                'checked' => $this->id() == 3002 ? 1 : 0,
            ),
        );
    }

}
