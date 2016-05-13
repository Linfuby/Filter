<?php
namespace Meling\Filter\Lists;

class Sexes extends \Meling\Filter\Lists\ImplementationChecked implements ListChecked
{
    /**
     * @return bool
     */
    public function active()
    {
        return !($this->id() == 3003 || !$this->id());
    }

    /**
     * @return Items\Item[]
     */
    protected function generateItems()
    {
        return array(
            '3003' => $this->buildItem('3003', 'Весь ассортимент', $this->id() == 3003),
            '3001' => $this->buildItem('3001', 'Женское', $this->id() == 3003),
            '3002' => $this->buildItem('3002', 'Мужское', $this->id() == 3003),
        );
    }


}
