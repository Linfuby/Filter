<?php
namespace Meling\Tests\Filter\Sexes;

/**
 * Class SeasonsTest
 * @package Meling\Tests\Filter\Sexes
 */
class SeasonsTest extends \Meling\Tests\TestCase
{
    /** @var \Meling\Filter\Sexes\Seasons */
    protected $seasons;

    public function setUp()
    {
        $this->seasons = new \Meling\Filter\Sexes\Seasons($this->getFilter(['sexes' => [3002 => []]]), $this->getFilter()->sexes()->get(), [-20141]);
    }

    public function testAsArray()
    {
        foreach($this->seasons->asArray() as $season) {
            $this->assertInstanceOf('Meling\Filter\Sexes\Seasons\Season', $season);
        }
    }

    /**
     * @throws \Exception
     */
    public function testGet()
    {
        $this->seasons->asArray();
        $this->assertInstanceOf('Meling\Filter\Sexes\Seasons\Season', $this->seasons->get(-20141));
    }

    /**
     * @expectedException \Exception
     */
    public function testGetThrow()
    {
        $this->seasons->get(2014);
    }

    public function testSelected()
    {
        $this->assertEquals(array(-20141), $this->seasons->selected());
    }

}
