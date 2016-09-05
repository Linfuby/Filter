<?php
namespace Meling\Tests\Filter\Sexes\Seasons;

class SeasonTest extends \Meling\Tests\TestCase
{
    /**
     * @var \Meling\Filter\Sexes\Seasons\Season
     */
    protected $season;

    public function setUp()
    {
        $this->season = new \Meling\Filter\Sexes\Seasons\Season(1, 'Season', true);
    }

    public function testId()
    {
        $this->assertEquals(1, $this->season->id());
    }

    public function testName()
    {
        $this->assertEquals('Season', $this->season->name());
    }

    public function testSelectedFalse()
    {
        $this->season = new \Meling\Filter\Sexes\Seasons\Season(1, 'Season', false);
        $this->assertFalse($this->season->isSelect());
    }

    public function testSelectedTrue()
    {
        $this->assertTrue($this->season->isSelect());
    }

}
