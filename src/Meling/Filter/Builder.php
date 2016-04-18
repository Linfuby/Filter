<?php
namespace Meling\Filter;

class Builder
{
    /**
     * @var \PHPixie\ORM
     */
    protected $orm;

    /**
     * @var \PHPixie\HTTP\Request
     */
    protected $request;

    /**
     * Builder constructor.
     * @param \PHPixie\ORM          $orm
     * @param \PHPixie\HTTP\Request $request
     */
    public function __construct(\PHPixie\ORM $orm, \PHPixie\HTTP\Request $request)
    {
        $this->orm     = $orm;
        $this->request = $request;
    }

    public function lists()
    {
        //TODO
    }

    public function products()
    {
        return $this->orm->query('product');
    }

    public function request()
    {
        return $this->request;
    }
}