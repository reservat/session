<?php

namespace Reservat\Session\Datamapper;

use Reservat\Core\Interfaces\SQLDatamapperInterface;
use Reservat\Core\Datamapper\PDODatamapper;

class PDOSessionDatamapper extends PDODatamapper implements SQLDatamapperInterface
{
    /**
     * Return the table name we're interacting with.
     *
     * @return string
     */
    public function table()
    {
        return 'session';
    }

    public function deleteBySessionId($sessionId)
    {
    	$query = 'DELETE FROM '.$this->table().' WHERE id = ?';
        $this->execute($query, array($sessionId));
    }

    public function deleteExpired()
    {
        $query = 'DELETE FROM '.$this->table().' WHERE expires < UNIX_TIMESTAMP()';
        $this->execute($query);
    }
    
}
