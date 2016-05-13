<?php
namespace Meling\Filter;

class Builder
{
    /**
     * @var \PHPixie\Database\Connection
     */
    protected $connection;

    /**
     * @var \PHPixie\Slice\Type\ArrayData\Editable
     */
    protected $data;

    /**
     * @var array
     */
    protected $instances = array();

    /**
     * @var \PHPixie\Database\Driver\PDO\Query\Type\Select
     */
    protected $query;

    /**
     * Builder constructor.
     * @param \PHPixie\Database\Connection           $connection
     * @param \PHPixie\Slice\Type\ArrayData\Editable $data
     */
    public function __construct(\PHPixie\Database\Connection $connection, \PHPixie\Slice\Type\ArrayData\Editable $data)
    {
        $this->connection = $connection;
        $this->data       = $data;
    }

    /**
     * @return Lists\Categories
     */
    public function categories()
    {
        return $this->instance('categories');
    }

    /**
     * @return \PHPixie\Database\Connection
     */
    public function connection()
    {
        return $this->connection;
    }

    public function firstQuery()
    {
        if($this->query === null) {
            $this->query = $this->connection()->selectQuery();
            if($this->sexes()->active()) {
                $this->query->where('products.sexId', $this->sexes()->id());
            }
            if($this->categories()->active()) {
                $this->query->where('productTypes.categoryId', 'in', $this->categories()->ids());
            }
            if($this->types()->active()) {
                $this->query->where('products.productTypeId', 'in', $this->types()->ids());
            }
        }

        return $this->query;
    }

    /**
     * @return Groups
     */
    public function groups()
    {
        return $this->instance('groups');
    }

    public function secondQuery($except = null)
    {
        $query = $this->firstQuery();
        if($this->groups()->active()) {
            foreach($this->groups()->asArray() as $group) {
                if($group->name() !== $except) {
                    $group->updateQuery($query);
                }
            }
        }
    }

    /**
     * @return Lists\Sexes
     */
    public function sexes()
    {
        return $this->instance('sexes');
    }

    /**
     * @return Lists\Types
     */
    public function types()
    {
        return $this->instance('types');
    }

    protected function buildCategories()
    {
        return new Lists\Categories($this, $this->data->arraySlice('categories'));
    }

    protected function buildGroups()
    {
        return new Groups($this, $this->types()->get($this->data->getRequired('typeId')));
    }

    protected function buildSexes()
    {
        return new Lists\Sexes($this, $this->data->arraySlice('sexes'));
    }

    protected function buildTypes()
    {
        return new Lists\Types($this, $this->data->arraySlice('types'));
    }

    protected function instance($name)
    {
        if(!array_key_exists($name, $this->instances)) {
            $method                 = 'build' . ucfirst($name);
            $this->instances[$name] = $this->$method();
        }

        return $this->instances[$name];
    }

}
