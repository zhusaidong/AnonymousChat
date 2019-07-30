<?php
/**
 * Client
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace Zhusaidong\AnonymousChat;

use Workerman\Connection\ConnectionInterface;

class Client
{
	/**
	 * @var User $user
	 */
	private $user = NULL;
	/**
	 * @var ConnectionInterface $connection
	 */
	private $connection = NULL;
	
	/**
	 * Client constructor.
	 */
	public function __construct()
	{
		$user = new User();
		$user->setId(md5(microtime(TRUE) . uniqid()));
		
		$this->setUser($user);
	}
	
	/**
	 * set user
	 *
	 * @param User $user
	 *
	 * @return Client
	 */
	public function setUser(User $user) : Client
	{
		$this->user = $user;
		
		return $this;
	}
	
	/**
	 * set connection
	 *
	 * @param ConnectionInterface $connection
	 *
	 * @return Client
	 */
	public function setConnection(ConnectionInterface $connection) : Client
	{
		$this->connection = $connection;
		
		return $this;
	}
	
	/**
	 * get user
	 *
	 * @return User
	 */
	public function getUser() : User
	{
		return $this->user;
	}
	
	/**
	 * get connection
	 *
	 * @return ConnectionInterface
	 */
	public function getConnection() : ConnectionInterface
	{
		return $this->connection;
	}
	
	/**
	 * toString
	 *
	 * @return string
	 */
	public function __toString() : string
	{
		return (string)$this->getUser();
	}
}
