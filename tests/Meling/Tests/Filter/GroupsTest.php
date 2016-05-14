<?php
namespace Meling\Tests\Filter;


class GroupsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPixie\Database
     */
    protected $database;
    /**
     * @var \Meling\Filter\Groups
     */
    protected $groups;

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
                'typeId' => '952749142001',
                'groups' => array(
                    '952749142001' => array(
                        'sizes'  => array(1),
                        'girths' => array(2),
                        'colors' => array(3),
                        'attr-1' => array(4),
                        'attr-2' => array(5),
                    )
                ),
            )
        );
        $builder        = new \Meling\Filter\Builder($connection, $data);
        $category       = new \Meling\Filter\Lists\Items\Item('2', 'Test Category', true);
        $type           = new \Meling\Filter\Lists\Items\Type('952749142001', 'Test Type', true, $category);
        $this->groups   = new \Meling\Filter\Groups($builder, $type, $data->arraySlice('groups'));
    }

    public function tearDown()
    {
        $this->database->get()->disconnect();
    }

    public function test()
    {
        $this->assertTrue($this->groups->active());
    }

    public function testAttributeBuilder()
    {
        $this->assertAttributeInstanceOf('\Meling\Filter\Builder', 'builder', $this->groups);
    }

    public function testAttributeData()
    {
        $this->assertAttributeInstanceOf('\PHPixie\Slice\Type\ArrayData\Slice', 'data', $this->groups);
    }

    public function testAttributeType()
    {
        $this->assertAttributeInstanceOf('\Meling\Filter\Lists\Items\Type', 'type', $this->groups);
    }

    public function testMethodAsArray()
    {
        foreach ($this->groups->asArray() as $type) {
            $this->assertInstanceOf('\Meling\Filter\Groups\Group', $type);
        }
    }

    public function testMethodGet()
    {
        $this->assertInstanceOf('\Meling\Filter\Groups\Group', $this->groups->get('colors'));
    }

}
