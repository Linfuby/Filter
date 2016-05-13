<?php
namespace Meling\Tests\Filter\Lists;

class CategoriesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Meling\Filter\Lists\Categories
     */
    protected $categories;

    /**
     * @var \PHPixie\Database
     */
    protected $database;

    public function setUp()
    {
        $slice            = new \PHPixie\Slice();
        $this->database   = new \PHPixie\Database(
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
        $connection       = $this->database->get();
        $data             = $slice->editableArrayData(
            array(
                'sexes'      => 3001,
                'categories' => array(
                    2,
                    10,
                ),
            )
        );
        $builder          = new \Meling\Filter\Builder($connection, $data);
        $this->categories = new \Meling\Filter\Lists\Categories($builder, $data->arraySlice('categories'));
    }

    public function tearDown()
    {
        $this->database->get()->disconnect();
    }

    public function testAttributeBuilder()
    {
        $this->assertAttributeInstanceOf('\Meling\Filter\Builder', 'builder', $this->categories);
    }

    public function testAttributeData()
    {
        $this->assertAttributeInstanceOf('\PHPixie\Slice\Type\ArrayData\Slice', 'data', $this->categories);
    }

    public function testMethodActive()
    {
        $this->assertTrue($this->categories->active());
    }

    public function testMethodActiveFalse()
    {
        /** @var \Meling\Filter\Builder $builder */
        $builder          = $this->getMock('\Meling\Filter\Builder', array(), array(), '', false);
        $slice            = new \PHPixie\Slice();
        $data             = $slice->editableArrayData();
        $this->categories = new \Meling\Filter\Lists\Categories($builder, $data->arraySlice('categories'));
        $this->assertFalse($this->categories->active());
    }

    public function testMethodAsArray()
    {
        foreach($this->categories->asArray() as $category) {
            $this->assertInstanceOf('\Meling\Filter\Lists\Items\Item', $category);
        }
    }

    public function testMethodGet()
    {
        $this->assertInstanceOf('\Meling\Filter\Lists\Items\Item', $this->categories->get('10'));
    }

    public function testMethodId()
    {
        $this->assertEquals(array(2, 10), $this->categories->ids());
    }

}
