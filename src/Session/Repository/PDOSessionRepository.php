<?php

namespace Reservat\Session\Repository;

use Reservat\Core\Repository\PDORepository;

class PDOSessionRepository extends PDORepository
{
    /**
     * Return a the table name.
     *
     * @return string
     */
    public function table()
    {
        return 'session';
    }

    public static $fillable = [
    	'session_id' => 'sessionId',
    	'user_id' => 'userId'
    ];

    public function getBySessionId($sessionId)
    {
    	$data = $this->query(array('session_id' => $sessionId), 1);

        if ($data->execute(array($sessionId))) {
            $this->records[] = $data->fetch(\PDO::FETCH_ASSOC);
        }

        return $this;
    }

}
