<?php
namespace Meling\Filter\Groups;

abstract class Group
{
    /**
     * @var \Meling\Filter\Builder
     */
    protected $builder;
    /**
     * @var array
     */
    protected $data;
    /**
     * @var \PHPixie\Database\Driver\PDO\Query\Type\Select
     */
    protected $firstQuery;
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $name;

    /**
     * Group constructor.
     *
     * @param \Meling\Filter\Builder                         $builder
     * @param \PHPixie\Database\Driver\PDO\Query\Type\Select $firstQuery
     * @param string                                         $id
     * @param string                                         $name
     * @param array                                          $data
     */
    public function __construct($builder, $firstQuery, $id, $name, array $data)
    {
        $this->builder    = $builder;
        $this->firstQuery = $firstQuery;
        $this->id         = $id;
        $this->name       = $name;
        $this->data       = $data;
    }

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }

    public function selected()
    {
        return $this->data;
    }

    public abstract function updateQuery();

}
