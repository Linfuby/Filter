<?php
namespace Meling\Filter;

/**
 * Class Sexes
 * @package Meling\Filter
 * @since   2.0
 */
class Sexes
{
    /** @var \Meling\Filter */
    protected $filter;

    /** @var \PHPixie\Slice\Type\ArrayData\Slice */
    protected $data;

    /** @var Sexes\Sex[] */
    private $sexes;

    /**
     * Sexes constructor.
     * @param \Meling\Filter                      $filter
     * @param \PHPixie\Slice\Type\ArrayData\Slice $data
     * @since 2.0
     */
    public function __construct(\Meling\Filter $filter, \PHPixie\Slice\Type\ArrayData\Slice $data)
    {
        $this->filter = $filter;
        $this->data   = $data;
    }

    /**
     * @return Sexes\Sex[]
     * @since 2.0
     */
    public function asArray()
    {
        $this->requireSexes();

        return $this->sexes;
    }

    /**
     * @param int $id
     * @return Sexes\Sex
     * @throws \Exception
     * @since 2.0
     */
    public function get($id = null)
    {
        $this->requireSexes();
        if($id === null) {
            $id = $this->selected();
        }
        if(array_key_exists($id, $this->sexes)) {
            return $this->sexes[$id];
        }
        throw new \Exception();
    }

    /**
     * @return mixed
     * @since 2.0
     */
    public function selected()
    {
        $sexes = $this->data->keys();
        return count($sexes) === 1 ? current($sexes) : 3003;
    }

    /**
     * @since 2.0
     */
    private function requireSexes()
    {
        if($this->sexes !== null) {
            return;
        }
        $sexes = [];
        foreach([3001 => 'Женское', 3002 => 'Мужское', 3003 => 'Весь ассортимент'] as $sexId => $sexName) {
            $sexes[$sexId] = new Sexes\Sex($this->filter, $this->data->arraySlice($sexId), $sexId, $sexName, $this->selected() == $sexId);
        }
        $this->sexes = $sexes;
    }

}
