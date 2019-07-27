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
	 * @var null|User $user
	 */
	private $user = NULL;
	/**
	 * @var null|ConnectionInterface $connection
	 */
	private $connection = NULL;
	
	public function __construct()
	{
		$user = new User();
		$user->setId(md5(microtime(TRUE) . uniqid()));
		
		$this->setUser($user);
	}
	
	/**
	 * @param User $user
	 *
	 * @return Client
	 */
	public function setUser(User $user)
	{
		$this->user = $user;
		
		return $this;
	}
	
	/**
	 * @param ConnectionInterface $connection
	 *
	 * @return Client
	 */
	public function setConnection(ConnectionInterface $connection)
	{
		$this->connection = $connection;
		
		return $this;
	}
	
	/**
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}
	
	/**
	 * @return ConnectionInterface
	 */
	public function getConnection()
	{
		return $this->connection;
	}
	
	/**
	 * toString
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string)$this->getUser();
	}
}
