<?php

namespace Reservat\Session\Entities;

use Reservat\Core\Interfaces\EntityInterface;
use Reservat\Core\Entity;

class PDOSession extends Entity implements EntityInterface
{

    protected $sessionId = null;

    protected $userId = null;

    protected $data = null;

    protected $expires = null;

    public function __construct($sessionId = null, $userId = null, $data = null, $expires = null)
    {
        $this->sessionId = $sessionId;
        $this->userId = $userId;
        $this->data = $data;
        $this->expires = $expires;
    }

    public function setSessionId($id)
    {
        $this->sessionId = $id;
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getUserId($userId)
    {
        return $this->userId;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData($data)
    {
        return $this->data;
    }

    public function setExpires($expires)
    {
        $this->expires = $expires;
    }

    public function getExpires()
    {
        return $expires;
    }

    public function toArray()
    {
        return [
            'session_id' => $this->sessionId,
            'user_id' => $this->userId,
            'data' => $this->data,
            'expires' => $this->expires,
        ];
    }
}
