<?php
namespace Meling\Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $database;

    protected $filter;

    protected function getDatabase()
    {
        if($this->database === null) {
            $slice          = new \PHPixie\Slice();
            $config         = $slice->arrayData(
                [
                    'default' => [
                        'driver'     => 'pdo',
                        'connection' => 'mysql:host=localhost;dbname=parishop_pixie',
                        'database'   => 'parishop_pixie',
                        'user'       => 'parishop',
                        'password'   => 'xd7pL2yvcL9yXUZ8fE7C',
                    ],

                ]
            );
            $this->database = new \PHPixie\Database($config);
        }

        return $this->database;
    }

    protected function getFilter($data = null)
    {
        if($this->filter === null) {
            $slice        = new \PHPixie\Slice();
            $data         = $slice->editableArrayData($data);
            $this->filter = new \Meling\Filter($this->getDatabase()->get(), $data);
        }

        return $this->filter;
    }

    protected function tearDown()
    {
        $this->getDatabase()->get()->disconnect();
    }

}
