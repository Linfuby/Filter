<?php
namespace Meling\Tests\Filter\Sexes\Categories;

/**
 * Class ProductTypesTest
 * @package Meling\Tests\Filter\Sexes\Categories
 */
class ProductTypesTest extends \Meling\Tests\TestCase
{
    /** @var \Meling\Filter\Sexes\Categories\ProductTypes */
    protected $productTypes;

    public function setUp()
    {
        $slice        = new \PHPixie\Slice();
        $data         = $slice->arrayData(
            [
                '33064618001' => [],
            ]
        );
        $this->productTypes = new \Meling\Filter\Sexes\Categories\ProductTypes($this->getFilter(['sexes' => [3001 => []]]), $this->getFilter()->sexes()->get()->categories()->get(10), $data->arraySlice());
    }

    /**
     *
     */
    public function testAsArray()
    {
        foreach($this->productTypes->asArray() as $productType) {
            $this->assertInstanceOf('Meling\Filter\Sexes\Categories\ProductTypes\ProductType', $productType);
        }
    }

    /**
     * @throws \Exception
     */
    public function testGet()
    {
        $this->productTypes->asArray();
        $this->assertInstanceOf('Meling\Filter\Sexes\Categories\ProductTypes\ProductType', $this->productTypes->get('33064618001'));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetThrow()
    {
        $this->productTypes->get(1);
    }

    /**
     *
     */
    public function testSelected()
    {
        $this->assertEquals(array('33064618001'), $this->productTypes->selected());
    }

}
