<?php
namespace Meling\Filter\Sexes;

/**
 * Class Sex
 * @package Meling\Filter\Sexes
 * @since   2.0
 */
class Sex
{
    /** @var \Meling\Filter */
    protected $filter;

    /** @var \PHPixie\Slice\Type\ArrayData\Slice */
    protected $data;

    /** @var mixed */
    protected $id;

    /** @var string */
    protected $name;

    /** @var bool */
    protected $isSelect;

    /** @var Categories */
    private $categories;

    /** @var Brands */
    private $brands;

    /** @var Seasons */
    private $seasons;

    /** @var Prices */
    private $prices;

    /**
     * Sex constructor.
     * @param \Meling\Filter                      $filter
     * @param \PHPixie\Slice\Type\ArrayData\Slice $data
     * @param mixed                               $id
     * @param string                              $name
     * @param bool                                $isSelect
     * @since 2.0
     */
    public function __construct(\Meling\Filter $filter, \PHPixie\Slice\Type\ArrayData\Slice $data, $id, $name, $isSelect = false)
    {
        $this->filter   = $filter;
        $this->data     = $data;
        $this->id       = $id;
        $this->name     = $name;
        $this->isSelect = $isSelect;
    }

    /**
     * @return Brands
     * @since 2.0
     */
    public function brands()
    {
        if($this->brands === null) {
            $this->brands = new Brands($this->filter, $this, $this->data->get('brands', []));
        }

        return $this->brands;
    }

    /**
     * @return Categories
     * @since 2.0
     */
    public function categories()
    {
        if($this->categories === null) {
            $this->categories = new Categories($this->filter, $this, $this->data->arraySlice('categories'));
        }

        return $this->categories;
    }

    /**
     * @return mixed
     * @since 2.0
     */
    public function id()
    {
        return (int)$this->id;
    }

    /**
     * @return string
     * @since 2.0
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return Prices
     * @since 2.0
     */
    public function prices()
    {
        if($this->prices === null) {
            $this->prices = new Prices($this->filter, $this, $this->data->arraySlice('prices'));
        }

        return $this->prices;
    }

    /**
     * @return Seasons
     * @since 2.0
     */
    public function seasons()
    {
        if($this->seasons === null) {
            $this->seasons = new Seasons($this->filter, $this, $this->data->get('seasons', []));
        }

        return $this->seasons;
    }

    /**
     * @return bool
     * @since 2.0
     */
    public function selected()
    {
        return (bool)$this->isSelect;
    }

}

