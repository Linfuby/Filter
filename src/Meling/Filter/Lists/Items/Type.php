<?php
/**
 * Created by PhpStorm.
 * User: manager
 * Date: 13.05.2016
 * Time: 16:31
 */

namespace Meling\Filter\Lists\Items;

class Type extends Item
{
    /**
     * @var Item
     */
    protected $category;

    /**
     * Type constructor.
     * @param mixed  $id
     * @param string $name
     * @param bool   $selected
     * @param Item   $category
     */
    public function __construct($id, $name, $selected, Item $category)
    {
        parent::__construct($id, $name, $selected);
        $this->category = $category;
    }
}