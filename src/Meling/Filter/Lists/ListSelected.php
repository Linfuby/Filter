<?php
namespace Meling\Filter\Lists;

interface ListSelected
{
    public function active();

    public function asArray();

    public function get($id);

    public function ids();

}
