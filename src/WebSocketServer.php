<?php
/**
 * WebSocket Server
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace Zhusaidong\AnonymousChat;

use Workerman\Connection\ConnectionInterface;
use Workerman\Worker;

class WebSocketServer
{
	/**
	 * @var string $verifyOrigin
	 */
	private $verifyOrigin = '';
	/**
	 * @var int $port
	 */
	private $port = 8001;
	/**
	 * @var string $serverLog server log
	 */
	private $serverLog = NULL;
	/**
	 * @var Worker $worker
	 */
	private $worker = NULL;
	/**
	 * @var AnonymousChat $anonymousChat
	 */
	private $anonymousChat = NULL;
	
	/**
	 * WebSocketServer constructor.
	 *
	 * @param int $port
	 */
	public function __construct($port = 0)
	{
		!empty($port) and $this->setPort($port);
		
		$this->anonymousChat = new AnonymousChat();
	}
	
	/**
	 * @param string $verifyOrigin
	 *
	 * @return WebSocketServer
	 */
	public function setVerifyOrigin(string $verifyOrigin) : WebSocketServer
	{
		$this->verifyOrigin = $verifyOrigin;
		
		return $this;
	}
	
	/**
	 * @param int $port
	 *
	 * @return WebSocketServer
	 */
	public function setPort(int $port = 8001) : WebSocketServer
	{
		$this->port = $port;
		
		return $this;
	}
	
	/**
	 * set server log
	 *
	 * @param string $serverLog
	 *
	 * @return WebSocketServer
	 */
	public function setServerLog(string $serverLog = 'anonymousChat.log') : WebSocketServer
	{
		$this->serverLog = $serverLog;
		
		return $this;
	}
	
	/**
	 * run
	 */
	public function run()
	{
		$this->worker = new Worker('websocket://0.0.0.0:' . $this->port);
		
		$this->worker->name  = 'ac ws server';
		$this->worker->count = 1;
		
		$this->worker->onWebSocketConnect = function(ConnectionInterface $connection)
		{
			$this->onConnect($connection);
		};
		$this->worker->onMessage          = function(ConnectionInterface $connection, $data)
		{
			$this->onMessage($connection, $data);
		};
		$this->worker->onClose            = function(ConnectionInterface $connection)
		{
			$this->onClose($connection);
		};
		$this->worker->onError            = function(ConnectionInterface $connection, $code, $msg)
		{
			$this->onError($connection, $code, $msg);
		};
		
		if($this->serverLog != NULL)
		{
			Worker::$stdoutFile = $this->serverLog;
			Worker::$logFile    = $this->serverLog;
		}
		
		if(PHP_OS == 'WINNT')
		{
			Worker::runAll();
		}
	}
	
	/**
	 * onConnect
	 *
	 * @param ConnectionInterface $connection
	 */
	private function onConnect(ConnectionInterface $connection)
	{
		if(!empty($this->verifyOrigin) and $_SERVER['HTTP_ORIGIN'] != $this->verifyOrigin)
		{
			$connection->close();
		}
		
		$client = new Client();
		$client->setConnection($connection);
		
		$this->anonymousChat->addClient($client);
		
		$connection->userId = $client->getUser()->getId();
		
		call_user_func_array([$this->anonymousChat, 'connect'], [$connection->userId]);
		
		Worker::safeEcho($connection->userId . ' ' . __FUNCTION__ . PHP_EOL);
	}
	
	/**
	 * onMessage
	 *
	 * @param ConnectionInterface $connection
	 * @param                     $input
	 */
	private function onMessage(ConnectionInterface $connection, $input)
	{
		Worker::safeEcho($connection->userId . ' ' . __FUNCTION__ . PHP_EOL);
		
		$info = json_decode($input, TRUE);
		
		$command = $info['command'];
		$data    = $info['data'] ?? '';
		
		if(method_exists($this->anonymousChat, $command))
		{
			call_user_func_array([$this->anonymousChat, $command], [$connection->userId, $data]);
		}
	}
	
	/**
	 * onClose
	 *
	 * @param ConnectionInterface $connection
	 */
	private function onClose(ConnectionInterface $connection)
	{
		Worker::safeEcho($connection->userId . ' ' . __FUNCTION__ . PHP_EOL);
		
		$connection->close();
		
		$this->anonymousChat->removeClient($connection->userId);
		
		call_user_func_array([$this->anonymousChat, 'close'], [$connection->userId]);
	}
	
	/**
	 * onError
	 *
	 * @param ConnectionInterface $connection
	 * @param                     $code
	 * @param                     $msg
	 */
	private function onError(ConnectionInterface $connection, $code, $msg)
	{
		Worker::safeEcho($connection->userId . ' ' . __FUNCTION__ . PHP_EOL);
		
		$connection->close();
		
		var_dump($code, $msg);
	}
}
