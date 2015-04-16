<?php

namespace Reservat\Session;

class Session {

	protected $handler = null;

	public function __construct($di, $handler = 'PDO')
	{	
		$handler = '\Reservat\Session\Handlers\\' . $handler;

		if(!class_exists($handler)){
			throw new \InvalidArgumentException('The Handler ' . $handler . ' could not be found');
		}

		$this->handler = new $handler($di);

		session_set_save_handler($this->handler, true);

		return $this;
	}

	public function start()
	{
		session_start();
	}

	public function get($key)
	{
		return $_SESSION[$key];
	}

	public function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

}
