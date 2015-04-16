<?php

namespace Reservat\Test;

use Aura\Di\Container;
use Aura\Di\Factory;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    protected $di = null;

    public function setUp()
    {
        $schema =<<<SQL
        CREATE TABLE "session" (
        "id" INTEGER PRIMARY KEY,
        "session_id" VARCHAR NOT NULL,
        "user_id" VARCHAR NOT NULL,
        "data" TEXT,
        "expires" INT
        );
SQL;

        $this->di = new Container(new Factory);
        $this->di->set('db', function () {
            return new \PDO('sqlite::memory:');
        });

        $this->di->get('db')->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->di->get('db')->exec($schema);
    }

    public function testSession()
    {
        $session = new \Reservat\Session\Session($this->di, 'PDO');
        $session->set('ohhai', 1);
    }

    public function testGetSession()
    {
        $session = new \Reservat\Session\Session($this->di, 'PDO');
        var_dump($session->get('ohhai'));
    }
}
