<?php
namespace Meling\Filter\Lists;

interface Lists
{
    public function active();

    public function asArray();

    public function get($id);

    public function id();

}
