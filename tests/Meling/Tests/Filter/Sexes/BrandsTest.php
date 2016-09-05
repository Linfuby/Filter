<?php
namespace Meling\Tests\Filter\Sexes;

/**
 * Class BrandsTest
 * @package Meling\Tests\Filter\Sexes
 */
class BrandsTest extends \Meling\Tests\TestCase
{
    /** @var \Meling\Filter\Sexes\Brands */
    protected $brands;

    public function setUp()
    {
        $this->brands = new \Meling\Filter\Sexes\Brands($this->getFilter(['sexes' => [3002 => []]]), $this->getFilter()->sexes()->get(), [-14727770]);
    }

    /**
     *
     */
    public function testAsArray()
    {
        foreach($this->brands->asArray() as $brand) {
            $this->assertInstanceOf('Meling\Filter\Sexes\Brands\Brand', $brand);
        }
    }

    /**
     * @throws \Exception
     */
    public function testGet()
    {
        $this->brands->asArray();
        $this->assertInstanceOf('Meling\Filter\Sexes\Brands\Brand', $this->brands->get(-14727770));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetThrow()
    {
        $this->brands->get(14727770);
    }

    /**
     *
     */
    public function testSelected()
    {
        $this->assertEquals(array(-14727770), $this->brands->selected());
    }

}
