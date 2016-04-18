<?php
namespace Meling\Tests\Filter\Lists;

/**
 * Class SexesTest
 * @package Meling\Tests\Filter\Lists
 */
class SexesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Meling\Filter\Lists\Sexes
     */
    protected $sexes;

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
        $data        = $slice->editableArrayData(
            array('sexes' => 3001)
        );
        $sexId       = null;
        $this->sexes = new \Meling\Filter\Lists\Sexes($sexId);
    }

    public function testAttributeItems()
    {
        $this->assertAttributeEquals(null, 'items', $this->sexes);
    }

    public function testAttributeSexId()
    {
        $this->assertAttributeEquals(null, 'sexId', $this->sexes);
    }

    public function testMethodAsArray()
    {
        $this->assertInternalType('array', $this->sexes->asArray());
    }

    public function testMethodId()
    {
        $this->sexes->get(3001);
        $this->assertEquals(
            (object)array(
                'id'      => 0,
                'name'    => 'Весь ассортимент',
                'checked' => 1,
            ), $this->sexes->get(null)
        );
    }


}
