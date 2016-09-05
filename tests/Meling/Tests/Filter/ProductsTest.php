<?php
namespace Meling\Tests\Filter;

class ProductsTest extends \Meling\Tests\TestCase
{
    /** @var \Meling\Filter\Products */
    protected $products;

    public function setUp()
    {
        $this->products = new \Meling\Filter\Products($this->getFilter());
    }

    public function testQuery()
    {
        $this->assertInternalType('array', $this->products->selected());
    }


}
