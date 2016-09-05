<?php
namespace Meling\Tests\Filter;

/**
 * Class ActionsTest
 * Список Акций должен возвращать
 * 1. Список доступных акций
 * 2. Список выбранных акций
 * @package Meling\Tests\Filter
 */
class ActionsTest extends \Meling\Tests\TestCase
{
    /**
     * @var \Meling\Filter\Actions
     */
    protected $actions;

    /**
     * @constructor
     */
    public function setUp()
    {
        $this->actions = new \Meling\Filter\Actions($this->getFilter(['actions' => ['625591781001']]), ['625591781001']);
    }

    /**
     * Список доступных акций
     */
    public function testAsArray()
    {
        foreach($this->actions->asArray() as $action) {
            $this->assertInstanceOf('Meling\Filter\Actions\Action', $action);
        }
    }

    /**
     * Список выбранных акций
     */
    public function testSelected()
    {
        $this->assertEquals(array('625591781001'), $this->actions->selected());
    }

}
