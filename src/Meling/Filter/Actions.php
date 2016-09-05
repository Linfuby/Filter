<?php
namespace Meling\Filter;

/**
 * Class Actions
 * Список Акций (Распродажа, Акция, Весь Ассортимент)
 * @package Meling\Filter
 * @since   2.0
 */
class Actions
{
    /**
     * Фильтр
     * @var \Meling\Filter
     * @since 2.0
     */
    protected $filter;

    /**
     * Выбранные акции
     * @var array
     * @since 2.0
     */
    protected $selected;

    /**
     * Массив доступных акций
     * @var Actions\Action[]
     * @since 2.0
     */
    private $actions;

    /**
     * Actions constructor.
     * @param \Meling\Filter $filter   Фильтр
     * @param array          $selected Выбранные акции
     * @since 2.0
     */
    public function __construct(\Meling\Filter $filter, $selected = [])
    {
        $this->filter   = $filter;
        $this->selected = $selected;
    }

    /**
     * Массив доступных акций
     * @return Actions\Action[]
     * @since 2.0
     */
    public function asArray()
    {
        if($this->actions === null) {
            // Запрос списка Акций
            $query = $this->filter->connection()->selectQuery();
            // Поля для выборки
            $query->fields(
                array(
                    'actions.id',
                    'actions.name',
                )
            );
            // Таблица (Представление выводящее только доступные акции)
            $query->table(strtolower('allowActions'), 'actions');
            /** @var \PHPixie\Database\Result $result */
            $result = $query->execute();
            // Массив акций
            $actions = [];
            foreach($result->asArray() as $action) {
                $actions[$action->id] = new Actions\Action($action->id, $action->name, in_array($action->id, $this->selected()));
            }
            // Итоговое формирование списка
            $this->actions = $actions;
        }

        return $this->actions;
    }

    /**
     * Выбранные акции
     * @return array
     * @since 2.0
     */
    public function selected()
    {
        return $this->selected;
    }

}
