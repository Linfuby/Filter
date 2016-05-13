<?php
namespace Meling\Filter\Lists;

abstract class ImplementationSelected extends ImplementationChecked
{
    public function ids()
    {
        return $this->id();
    }

}
