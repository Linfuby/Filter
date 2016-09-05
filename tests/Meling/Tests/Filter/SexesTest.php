<?php
namespace Meling\Tests\Filter;

/**
 * Class SexesTest
 * Список должен возвращать
 * 1. Классы половой принадлежности
 * 2. Класс половой принадлежности
 * 3. Выбранную половую принадлежность
 * @package Meling\Tests\Filter
 */
class SexesTest extends \Meling\Tests\TestCase
{
    /**
     * @var \Meling\Filter\Sexes
     */
    protected $sexes;

    /**
     * @constructor
     */
    public function setUp()
    {
        $slice       = new \PHPixie\Slice();
        $data        = $slice->arrayData(
            [
                '3001' => [],
                '3002' => [],
            ]
        );
        $this->sexes = new \Meling\Filter\Sexes($this->getFilter(), $data->arraySlice());
    }

    /**
     * Классы половой принадлежности
     */
    public function testAsArray()
    {
        foreach($this->sexes->asArray() as $sex) {
            $this->assertInstanceOf('Meling\Filter\Sexes\Sex', $sex);
        }
    }

    /**
     * Класс половой принадлежности
     */
    public function testGet()
    {
        $this->sexes->asArray();
        $this->assertInstanceOf('Meling\Filter\Sexes\Sex', $this->sexes->get());
    }

    /**
     * @expectedException \Exception
     */
    public function testGetThrow()
    {
        $this->sexes->get(3000);
    }

    /**
     * Выбранную половую принадлежность
     */
    public function testSelected()
    {
        $this->assertEquals(3003, $this->sexes->selected());
    }

}
