<?php

namespace Reservat\Session\Handlers;

use Reservat\Session\DataMapper\PDOSessionDatamapper;
use Reservat\Session\Repository\PDOSessionRepository;
use Reservat\Session\Entities\PDOSession;

class PDO implements \SessionHandlerInterface {

    private $pdo;
    private $maxLifetime;

    protected $repo;
    protected $mapper;

    public function __construct($di, $config = array()) {
        $this->pdo = $di->get('db');
        $this->repo = new PDOSessionRepository($this->pdo);
        $this->mapper = new PDOSessionDatamapper($this->pdo);
        $this->maxLifetime = isset($config['maxLifetime']) ? $config['maxLifetime'] : ini_get('session.gc_maxlifetime');
    }
    /**
     * We don't need to do anything extra to initialize the session since
     * we get PDO in constructor
     */
    public function open($savePath, $name) { }

    /**
     * We need to clean up old sessions
     * @param  [type] $maxLifetime [description]
     */
    public function gc($maxLifetime) { }
 
    /**
     * Close the current session by disconnecting from mysql
     */
    public function close() {
        unset($this->pdo);
    }
 
    /**
     * Destroys the session by deleting the row from mysql
     * 
     * @param  string $sessionId The session id.
     */
    public function destroy($sessionId) {
        $this->mapper->deletebySessionId($sessionId);
    }

    /**
     * Read the session data from mysql.
     * 
     * @param  string $sessionId The session id.
     * @return string            The serialized session data.
     */
    public function read($sessionId) {
        $session = $this->repo->findBySessionId($sessionId)->getResults('Session\Entities\PDOSession');
        return $session;
    }
 
    /**
     * Write the serialized session data to Redis. This also sets
     * the Redis key EXPIRES time so we don't have to rely on the
     * PHP gc.
     * 
     * @param  string $sessionId   The session id.
     * @param  string $sessionData The serialized session data.
     */
    public function write($sessionId, $sessionData) {

        $results = $this->repo->getBySessionId($sessionId);
        $session = $results->getResults('Session\Entities\PDOSession');
        if($session){
            $session->setData($sessionData);
            $this->mapper->update($session, $session->id);
        } else {
            $session = new PDOSession($sessionId, 1, $sessionData, 600);
            $this->mapper->save($session);
        }

    }

}