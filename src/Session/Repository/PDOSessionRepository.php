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
    	$data = $this->sessionNotExpiredQuery(1);

        if ($data->execute(array($sessionId)) && $results = $data->fetch(\PDO::FETCH_ASSOC)) {
            $this->records[] = $results;
        }

        return $this;
    }

    protected function sessionNotExpiredQuery($limit)
    {   
        $query = 'SELECT * FROM '.$this->table().' WHERE ';
        $query .= 'session_id = ? AND expires > UNIX_TIMESTAMP()';

        $query = $query.' LIMIT '.intval($limit);
        $db = $this->db->prepare($query);

        return $db;
    }

}
