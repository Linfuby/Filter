<?php
namespace Meling\Tests\Filter\Lists;

class TypesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Meling\Filter\Lists\Types
     */
    protected $types;

    /**
     * @var \PHPixie\Database
     */
    protected $database;

    public function setUp()
    {
        $slice          = new \PHPixie\Slice();
        $this->database = new \PHPixie\Database(
            $slice->editableArrayData(
                array(
                    'default' => array(
                        'driver'     => 'pdo',
                        'connection' => 'mysql:host=localhost;dbname=parishop_pixie',
                        'user'       => 'parishop',
                        'password'   => 'xd7pL2yvcL9yXUZ8fE7C',
                        'database'   => 'parishop_pixie',
                    ),
                )
            )
        );
        $connection     = $this->database->get();
        $data           = $slice->editableArrayData(
            array(
                'sexes'      => 3001,
                'categories' => array(
                    2,
                    10,
                ),
                'types'      => array(952749142001),
            )
        );
        $builder        = new \Meling\Filter\Builder($connection, $data);
        $this->types    = new \Meling\Filter\Lists\Types($builder, $data->arraySlice('types'));
    }

    public function tearDown()
    {
        $this->database->get()->disconnect();
    }


    public function testAttributeBuilder()
    {
        $this->assertAttributeInstanceOf('\Meling\Filter\Builder', 'builder', $this->types);
    }

    public function testAttributeData()
    {
        $this->assertAttributeInstanceOf('\PHPixie\Slice\Type\ArrayData\Slice', 'data', $this->types);
    }

    public function testMethodActive()
    {
        $this->assertTrue($this->types->active());
    }

    public function testMethodActiveFalse()
    {
        /** @var \Meling\Filter\Builder $builder */
        $builder     = $this->getMock('\Meling\Filter\Builder', array(), array(), '', false);
        $slice       = new \PHPixie\Slice();
        $data        = $slice->editableArrayData();
        $this->types = new \Meling\Filter\Lists\Types($builder, $data->arraySlice('types'));
        $this->assertFalse($this->types->active());
    }

    public function testMethodAsArray()
    {
        foreach($this->types->asArray() as $type) {
            $this->assertInstanceOf('\Meling\Filter\Lists\Items\Type', $type);
        }
    }

    public function testMethodGet()
    {
        $this->assertInstanceOf('\Meling\Filter\Lists\Items\Type', $this->types->get('952749142001'));
    }

    public function testMethodId()
    {
        $this->assertEquals(array(952749142001), $this->types->ids());
    }

}
