<?php
namespace Meling\Tests\Filter\Sexes\Brands;

class BrandTest extends \Meling\Tests\TestCase
{
    /**
     * @var \Meling\Filter\Sexes\Brands\Brand
     */
    protected $brand;

    public function setUp()
    {
        $this->brand = new \Meling\Filter\Sexes\Brands\Brand(1, 'Brand', true);
    }

    public function testId()
    {
        $this->assertEquals(1, $this->brand->id());
    }

    public function testName()
    {
        $this->assertEquals('Brand', $this->brand->name());
    }

    public function testSelectedFalse()
    {
        $this->brand = new \Meling\Filter\Sexes\Brands\Brand(1, 'Brand', false);
        $this->assertFalse($this->brand->isSelect());
    }

    public function testSelectedTrue()
    {
        $this->assertTrue($this->brand->isSelect());
    }

}
