<?php
namespace Meling\Tests\Filter\Sexes\Categories;

class CategoryTest extends \Meling\Tests\TestCase
{
    /**
     * @var \Meling\Filter\Sexes\Categories\Category
     */
    protected $category;

    public function setUp()
    {
        $slice          = new \PHPixie\Slice();
        $data           = $slice->arrayData(['10' => []]);
        $this->category = new \Meling\Filter\Sexes\Categories\Category($this->getFilter(), $this->getFilter()->sexes()->get(), $data->arraySlice('10'), 10, 'Category', true);
    }

    public function testId()
    {
        $this->assertEquals(10, $this->category->id());
    }

    public function testName()
    {
        $this->assertEquals('Category', $this->category->name());
    }

    public function testProductTypes()
    {
        $this->assertInstanceOf('Meling\Filter\Sexes\Categories\productTypes', $this->category->productTypes());
    }

    public function testSelectedFalse()
    {
        $slice          = new \PHPixie\Slice();
        $data           = $slice->arrayData([]);
        $this->category = new \Meling\Filter\Sexes\Categories\Category($this->getFilter(), $this->getFilter()->sexes()->get(), $data->arraySlice(), 10, 'Category', false);
        $this->assertFalse($this->category->isSelect());
    }

    public function testSelectedTrue()
    {
        $this->assertTrue($this->category->isSelect());
    }

}
