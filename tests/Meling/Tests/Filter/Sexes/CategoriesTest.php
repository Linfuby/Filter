<?php
namespace Meling\Tests\Filter\Sexes;

/**
 * Class CategoriesTest
 * @package Meling\Tests\Filter\Sexes
 */
class CategoriesTest extends \Meling\Tests\TestCase
{
    /** @var \Meling\Filter\Sexes\Categories */
    protected $categories;

    public function setUp()
    {
        $slice            = new \PHPixie\Slice();
        $data             = $slice->arrayData(
            [
                '10' => [],
                '11' => [],
            ]
        );
        $this->categories = new \Meling\Filter\Sexes\Categories($this->getFilter(['sexes' => [3001 => []]]), $this->getFilter()->sexes()->get(), $data->arraySlice());
    }

    /**
     *
     */
    public function testAsArray()
    {
        foreach($this->categories->asArray() as $category) {
            $this->assertInstanceOf('Meling\Filter\Sexes\Categories\Category', $category);
        }
    }

    /**
     * @throws \Exception
     */
    public function testGet()
    {
        $this->categories->asArray();
        $this->assertInstanceOf('Meling\Filter\Sexes\Categories\Category', $this->categories->get(10));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetThrow()
    {
        $this->categories->get(1);
    }

    /**
     *
     */
    public function testSelected()
    {
        $this->assertEquals([10, 11], $this->categories->selected());
    }

}
