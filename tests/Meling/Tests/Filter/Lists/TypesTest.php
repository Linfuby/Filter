<?php
namespace Meling\Tests\Filter\Lists;

/**
 * Class TypesTest
 * @package Meling\Tests\Filter\Lists
 */
class TypesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Meling\Filter\Lists\Types
     */
    protected $types;

    public function setUp()
    {
        $slice       = new \PHPixie\Slice();
        $database    = new \PHPixie\Database(
            $slice->arrayData(
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
        $sexId       = null;
        $this->types = new \Meling\Filter\Lists\Types($database->get(), array(), array(10));
    }

    public function testAttributeItems()
    {
        $this->assertAttributeEquals(null, 'items', $this->types);
    }

    public function testAttributeSexId()
    {
        $this->assertAttributeEquals(array(), 'ids', $this->types);
    }

    public function testMethodAsArray()
    {
        $this->assertInternalType('array', $this->types->asArray());
    }

    public function testMethodId()
    {
        $this->types->get(3001);
        $this->assertEquals(
            (object)array(
                'id'      => 0,
                'name'    => 'Весь ассортимент',
                'checked' => 1,
            ), $this->types->get(null)
        );
    }


}
