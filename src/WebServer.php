<?php
/**
 * Web Server
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace Zhusaidong\AnonymousChat;

use Workerman\Worker;
use Workerman\WebServer as BaseWebServer;

class WebServer
{
	/**
	 * @var int $port
	 */
	private $port = 8000;
	
	/**
	 * WebServer constructor.
	 *
	 * @param int $port
	 */
	public function __construct($port = 0)
	{
		!empty($port) and $this->setPort($port);
	}
	
	/**
	 * set port
	 *
	 * @param int $port
	 *
	 * @return WebServer
	 */
	public function setPort(int $port) : WebServer
	{
		$this->port = $port;
		
		return $this;
	}
	
	/**
	 * run
	 */
	public function run() : void
	{
		$worker = new BaseWebServer('http://0.0.0.0:' . $this->port);
		
		$worker->name  = 'ac web server';
		$worker->count = 10;
		
		$worker->addRoot('localhost', realpath(__DIR__ . '/../web'));
		
		Worker::runAll();
	}
}
