<?php

namespace Reservat\Session\Handlers;

use Reservat\Session\DataMapper\PDOSessionDatamapper;
use Reservat\Session\Repository\PDOSessionRepository;
use Reservat\Session\Entities\PDOSession;
use Reservat\Core\Log;

class PDO implements \SessionHandlerInterface
{

    private $di;
    private $maxLifetime;

    protected $repo;
    protected $mapper;

    public function __construct($di, $config = array())
    {
        $this->di = $di;
        $this->repo = new PDOSessionRepository($this->di->get('db'));
        $this->mapper = new PDOSessionDatamapper($this->di->get('db'));
        $this->maxLifetime = isset($config['maxLifetime']) ? $config['maxLifetime'] : ini_get('session.gc_maxlifetime');
    }

    /**
     * Called by the Reservat session handler to initialise.
     * This should only be called **ONCE**
     */
    public function start()
    {
        // Better to be safe than sorry.
        if (!headers_sent()) {
            session_start();
        } else {
            Log::error('Tried to start session after headers sent. '.get_class());
        }
    }

    public function getRaw($sessionId)
    {
        $session = $this->repo->getBySessionId($sessionId)->getResults(new PDOSession());
        if ($session) {
            return $session;
        } else {
            return false;
        }
    }

    /**
     * We don't need to do anything extra to initialize the session since
     * we get PDO in constructor
     */
    public function open($savePath, $name)
    {
    }

    /**
     * We need to clean up old sessions
     * @param  [type] $maxLifetime [description]
     */
    public function gc($maxLifetime)
    {
        $this->mapper->deleteExpired();
    }
 
    /**
     * Close the current session by disconnecting from mysql
     */
    public function close()
    {
        unset($this->pdo);
    }
 
    /**
     * Destroys the session by deleting the row from mysql
     *
     * @param  string $sessionId The session id.
     */
    public function destroy($sessionId)
    {
        $this->mapper->deletebySessionId($sessionId);
    }

    /**
     * Read the session data from mysql.
     *
     * @param  string $sessionId The session id.
     * @return string            The serialized session data.
     */
    public function read($sessionId)
    {
        $session = $this->getRaw($sessionId);
        if ($session) {
            return $session->getData();
        } else {
            return false;
        }
    }
 
    /**
     * Write the serialized session data to mysql.
     *
     * @param  string $sessionId   The session id.
     * @param  string $sessionData The serialized session data.
     */
    public function write($sessionId, $sessionData)
    {
        try {
            $result = $this->repo->getBySessionId($sessionId)->getResults(new PDOSession());
            if ($result) {
                $result->setData($sessionData);
                $this->mapper->update($result, $result->id);
            } else {
                $date = new \DateTime();
                $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;
                $session = new PDOSession($sessionId, $userId, $sessionData, ($date->getTimestamp() + $this->maxLifetime));
                $this->mapper->save($session);
            }
        } catch (\Exception $e) {
            Log::debug('session error', [$e->getMessage()]);
        }
    }
}
