<?php
namespace Meling\Filter\Sexes;

use PHPixie\Database\Type\SQL\Expression;

/**
 * Class Prices
 * @package Meling\Filter\Sexes
 * @since   2.0
 */
class Prices
{
    /** @var \Meling\Filter */
    protected $filter;

    /** @var \PHPixie\Slice\Type\ArrayData\Slice */
    protected $data;

    /** @var Sex */
    protected $sex;

    /** @var int */
    private $max;

    /** @var int */
    private $min;

    /** @var int */
    private $from;

    /** @var int */
    private $to;

    /**
     * Prices constructor.
     * @param \Meling\Filter                      $filter
     * @param Sex                                 $sex
     * @param \PHPixie\Slice\Type\ArrayData\Slice $data
     * @since    2.0
     */
    public function __construct(\Meling\Filter $filter, Sex $sex, \PHPixie\Slice\Type\ArrayData\Slice $data)
    {
        $this->filter = $filter;
        $this->sex    = $sex;
        $this->data   = $data;
        $this->from   = $this->data->get('from');
        $this->to     = $this->data->get('to');
    }

    /**
     * @return int
     * @since 2.0
     */
    public function from()
    {
        $this->requirePrices();

        return $this->from;
    }

    public function id()
    {
        return $this->data->get('id');
    }

    /**
     * @return int
     * @since 2.0
     */
    public function max()
    {
        $this->requirePrices();

        return $this->max;
    }

    /**
     * @return int
     * @since 2.0
     */
    public function min()
    {
        $this->requirePrices();

        return $this->min;
    }

    public function name()
    {
        if($this->id() === null) {
            $result = '';
            if($this->from()) {
                $result .= ' От ' . $this->from();
            }
            if($this->to()) {
                $result .= ' До ' . $this->to();
            }

            return $result;
        }
        switch($this->id()) {
            case 1:
                return 'Высокая';
                break;
            case -1:
                return 'Низкая';
                break;
            default:
                return 'Средняя';
                break;
        }
    }

    /**
     * @return \object[]
     * @since 2.0
     */
    public function requirePrices()
    {
        if($this->max !== null && $this->min !== null) {
            return;
        }
        $query = $this->filter->connection()->selectQuery();
        $query->fields(
            array(
                'min' => new Expression('MIN(`options`.`price`)'),
                'max' => new Expression('MAX(`options`.`price`)'),
            )
        );
        $query->table('options');
        $query->join('products');
        $query->on('products.id', 'options.productId');
        if($this->sex->id() !== 3003) {
            $query->where('products.sexId', $this->sex->id());
        }
        /** @var \PHPixie\Database\Result $result */
        $result = $query->execute();
        foreach($result->asArray() as $price) {
            if($this->from && $this->from < $price->min) {
                $this->from = $price->min;
            }
            if($this->to && $this->to > $price->max) {
                $this->to = $price->max;
            }
            $this->max = $price->max;
            $this->min = $price->min;
        }
    }

    /**
     * @return int
     * @since 2.0
     */
    public function to()
    {
        $this->requirePrices();

        return $this->to;
    }

}

