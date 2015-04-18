<?php

namespace Reservat\Test;

use Aura\Di\Container;
use Aura\Di\Factory;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    protected $di = null;
    protected $sessionId;
    protected $sessionData;
    protected $session;

    public function __construct()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }

        ob_start();
    }

    public function setUp()
    {
        $schema =<<<SQL
        CREATE TABLE "session" (
        "id" INTEGER PRIMARY KEY,
        "session_id" VARCHAR NOT NULL,
        "user_id" INT,
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

        $testSessionData = [
            1,
            'k4o6898jdru8e8gah9mkv5fss5',
            14,
            'ohhai|i:1;userId|i:14;',
            (new \DateTime())->getTimeStamp() + 1000
        ];

        $sql = "INSERT INTO session (id, session_id, user_id, data, expires) VALUES ('". implode("','", $testSessionData) ."')";
        $this->di->get('db')->exec($sql);

    }

    public function testSetPDOSession()
    {

        $session = new \Reservat\Session\Session($this->di, 'PDO');

        $session->set('ohhai', 1);
        $session->set('userId', 12);

        $this->assertEquals($session->get('ohhai'), 1);
        $this->assertEquals($session->get('userId'), 12);

    }


    public function testGetPDOSession()
    {

        $session = new \Reservat\Session\Session($this->di, 'PDO');

        session_id('k4o6898jdru8e8gah9mkv5fss5');

        //session_decode($this->sessionData);

        $handler = $session->getHandler();
        session_decode($handler->read('k4o6898jdru8e8gah9mkv5fss5'));

        $this->assertEquals($session->get('ohhai'), 1);
        $this->assertEquals($session->get('userId'), 14);

        $rawSession = $handler->getRaw(session_id());
        $this->assertEquals($rawSession->getUserId(), 14);

    }
}
