<?php
namespace Meling\Filter;

class Groups
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var Lists\Items\Type
     */
    protected $type;

    /**
     * @var Groups\Group[]
     */
    protected $groups;

    /**
     * Extra constructor.
     * @param Builder          $builder
     * @param Lists\Items\Type $type
     */
    public function __construct(Builder $builder, Lists\Items\Type $type)
    {
        $this->builder = $builder;
        $this->type    = $type;
    }

    /**
     * @return Groups\Group[]
     */
    public function asArray()
    {
        $this->requireGroups();

        return $this->groups;
    }

    /**
     * @param $id
     * @return Groups\Group
     * @throws \Exception
     */
    public function get($id)
    {
        $this->requireGroups();
        if(array_key_exists($id, $this->groups)) {
            return $this->groups[$id];
        }
        throw new \Exception('Group ' . $id . ' does not exist');
    }

    protected function requireGroups()
    {
        if($this->groups !== null) {
            return;
        }
        $groups = array(
            'sizes' => $this->buildGroup($this->builder->firstQuery()),
        );

        $this->groups = $groups;
    }

}
