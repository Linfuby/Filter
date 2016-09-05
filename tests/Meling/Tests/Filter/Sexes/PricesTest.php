<?php
namespace Meling\Tests\Filter\Sexes;

/**
 * Class PricesTest
 * @package Meling\Tests\Filter\Sexes
 */
class PricesTest extends \Meling\Tests\TestCase
{
    /** @var \Meling\Filter\Sexes\Prices */
    protected $prices;

    public function setUp()
    {
        $slice        = new \PHPixie\Slice();
        $data         = $slice->arrayData(
            [
                'from' => 100,
                'to'   => 10000,
            ]
        );
        $this->prices = new \Meling\Filter\Sexes\Prices($this->getFilter(['sexes' => [3002 => []]]), $this->getFilter()->sexes()->get(), $data->arraySlice());
    }

    public function testFrom()
    {
        $this->assertEquals(250, $this->prices->from());
    }

    public function testId()
    {
        $this->assertEquals(null, $this->prices->id());
    }

    public function testMax()
    {
        $this->assertEquals(6050, $this->prices->max());
    }

    public function testMin()
    {
        $this->assertEquals(250, $this->prices->min());
    }

    public function testName()
    {
        $this->assertEquals(' От 250 До 6050', $this->prices->name());
    }

    public function testNameSegment0()
    {
        $slice        = new \PHPixie\Slice();
        $data         = $slice->arrayData(
            [
                'id' => 0,
            ]
        );
        $this->prices = new \Meling\Filter\Sexes\Prices($this->getFilter(['sexes' => [3002 => []]]), $this->getFilter()->sexes()->get(), $data->arraySlice());
        $this->assertEquals('Средняя', $this->prices->name());
    }

    public function testNameSegment1()
    {
        $slice        = new \PHPixie\Slice();
        $data         = $slice->arrayData(
            [
                'id' => 1,
            ]
        );
        $this->prices = new \Meling\Filter\Sexes\Prices($this->getFilter(['sexes' => [3002 => []]]), $this->getFilter()->sexes()->get(), $data->arraySlice());
        $this->assertEquals('Высокая', $this->prices->name());
    }

    public function testNameSegment2()
    {
        $slice        = new \PHPixie\Slice();
        $data         = $slice->arrayData(
            [
                'id' => -1,
            ]
        );
        $this->prices = new \Meling\Filter\Sexes\Prices($this->getFilter(['sexes' => [3002 => []]]), $this->getFilter()->sexes()->get(), $data->arraySlice());
        $this->assertEquals('Низкая', $this->prices->name());
    }

    public function testTo()
    {
        $this->prices->to();
        $this->assertEquals(6050, $this->prices->to());
    }

}
