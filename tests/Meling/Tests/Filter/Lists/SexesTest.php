<?php
namespace Meling\Tests\Filter\Lists;

class SexesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Meling\Filter\Lists\Sexes
     */
    protected $sexes;

    public function setUp()
    {
        /** @var \Meling\Filter\Builder $builder */
        $builder     = $this->getMock('\Meling\Filter\Builder', array(), array(), '', false);
        $slice       = new \PHPixie\Slice();
        $data        = $slice->editableArrayData(
            array(
                'sexes' => 3002,
            )
        );
        $this->sexes = new \Meling\Filter\Lists\Sexes($builder, $data->arraySlice('sexes'));
    }

    public function testAttributeBuilder()
    {
        $this->assertAttributeInstanceOf('\Meling\Filter\Builder', 'builder', $this->sexes);
    }

    public function testAttributeData()
    {
        $this->assertAttributeInstanceOf('\PHPixie\Slice\Type\ArrayData\Slice', 'data', $this->sexes);
    }

    public function testMethodActive()
    {
        $this->assertTrue($this->sexes->active());
    }

    public function testMethodActiveFalse()
    {
        /** @var \Meling\Filter\Builder $builder */
        $builder     = $this->getMock('\Meling\Filter\Builder', array(), array(), '', false);
        $slice       = new \PHPixie\Slice();
        $data        = $slice->editableArrayData();
        $this->sexes = new \Meling\Filter\Lists\Sexes($builder, $data->arraySlice('sexes'));
        $this->assertFalse($this->sexes->active());
    }

    public function testMethodAsArray()
    {
        foreach($this->sexes->asArray() as $sex) {
            $this->assertInstanceOf('\Meling\Filter\Lists\Items\Item', $sex);
        }
    }

    public function testMethodGet()
    {
        $this->assertInstanceOf('\Meling\Filter\Lists\Items\Item', $this->sexes->get('3001'));
    }

    public function testMethodId()
    {
        $this->assertEquals(3002, $this->sexes->id());
    }

}
