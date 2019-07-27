<?php
/**
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace Zhusaidong\AnonymousChat;

use Workerman\WebServer;
use Workerman\Worker;

class WebServer2 extends WebServer
{
	private $errorLog = '';
	
	public function __construct($socket_name, $context_option = array())
	{
		parent::__construct($socket_name, $context_option);
		
		function set_time_limit ($seconds) {}
	}
	/**
	 * @param string $errorLog
	 *
	 * @return WebServer2
	 */
	public function setErrorLog(string $errorLog) : WebServer2
	{
		$this->errorLog = $errorLog;
		
		return $this;
	}
	
	public function onMessage($connection)
	{
		var_dump('WebServer2::onMessage');
		try
		{
			parent::onMessage($connection);
		}
		catch(\Error $e)
		{
			if(!empty($this->errorLog))
			{
				file_put_contents($this->errorLog, var_export($e, TRUE), FILE_APPEND);
			}
			else
			{
				$connection->send($e);
			}
		}
	}
}
