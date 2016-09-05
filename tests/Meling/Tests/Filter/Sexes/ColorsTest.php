<?php
namespace Meling\Tests\Filter\Sexes;

/**
 * Class ColorsTest
 * @package Meling\Tests\Filter\Sexes
 */
class ColorsTest extends \Meling\Tests\TestCase
{
    /** @var \Meling\Filter\Sexes\Colors */
    protected $colors;

    public function setUp()
    {
        $this->colors = new \Meling\Filter\Sexes\Colors($this->getFilter(['sexes' => [3002 => []]]), $this->getFilter()->sexes()->get(), [-124259943]);
    }

    public function testAsArray()
    {
        foreach($this->colors->asArray() as $color) {
            $this->assertInstanceOf('Meling\Filter\Sexes\Categories\ProductTypes\Colors\Color', $color);
        }
    }

    public function testGet()
    {
        $this->colors->asArray();
        $this->assertInstanceOf('Meling\Filter\Sexes\Categories\ProductTypes\Colors\Color', $this->colors->get('-124259943'));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetThrow()
    {
        $this->colors->get('124259943');
    }

    public function testSelected()
    {
        $this->assertEquals(array(-124259943), $this->colors->selected());
    }

}
