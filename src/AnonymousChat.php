<?php
/**
 * AnonymousChat
 *
 * @author  zhusaidong [zhusaidong@gmail.com]
 * @version 0.1.0
 * @since   2019/07/24
 */

namespace Zhusaidong\AnonymousChat;

use Workerman\Connection\ConnectionInterface;

class AnonymousChat
{
	/**
	 * @var array $clients
	 */
	private $clients = [];
	/**
	 * @var SensitiveWord $sensitiveWord
	 */
	private $sensitiveWord = NULL;
	/**
	 * @var RandomNickName $randomNickName
	 */
	private $randomNickName = NULL;
	
	/**
	 * AnonymousChat constructor.
	 */
	public function __construct()
	{
		$this->sensitiveWord  = new SensitiveWord();
		$this->randomNickName = new RandomNickName();
	}
	
	/**
	 * add client
	 *
	 * @param Client $client
	 *
	 * @return AnonymousChat
	 */
	public function addClient(Client $client) : AnonymousChat
	{
		$user = $client->getUser();
		$user->setNickName($this->randomNickName->get());
		$this->clients[$user->getId()] = $client;
		
		return $this;
	}
	
	/**
	 * remove client
	 *
	 * @param string $clientUserId
	 *
	 * @return bool
	 */
	public function removeClient(string $clientUserId) : bool
	{
		if(isset($this->clients[$clientUserId]))
		{
			unset($this->clients[$clientUserId]);
			
			return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * send msg
	 *
	 * @param ConnectionInterface $connection
	 * @param string              $command
	 * @param array               $data
	 */
	private function send(ConnectionInterface $connection, string $command, array $data = []) : void
	{
		$data['status'] = 1;
		
		$connection->send(json_encode([
			'command' => $command,
			'data'    => $data,
		], JSON_UNESCAPED_UNICODE));
	}
	
	/**
	 * toString
	 *
	 * @return string
	 */
	public function __toString() : string
	{
		return 'clients(' . count($this->clients) . ')';
	}
	
	/**
	 * welcome
	 *
	 * @param $connectionUserId
	 */
	public function connect($connectionUserId) : void
	{
		/** @var Client $client */
		if(($client = $this->clients[$connectionUserId] ?? NULL) !== NULL)
		{
			$this->send($client->getConnection(), 'welcome', [
				'nickName' => $client->getUser()->getNickName(),
			]);
		}
		
		$this->online();
		$this->onlineList();
	}
	
	/**
	 * close
	 */
	public function close() : void
	{
		$this->online();
		$this->onlineList();
	}
	
	/**
	 * online
	 */
	public function online() : void
	{
		$clientCount = count($this->clients);
		/** @var Client $client */
		foreach($this->clients as $client)
		{
			$this->send($client->getConnection(), 'online', ['onlineNumber' => $clientCount]);
		}
	}
	
	/**
	 * changeNickName
	 *
	 * @param $connectionUserId
	 * @param $data
	 */
	public function changeNickName($connectionUserId, $data) : void
	{
		/** @var Client $client */
		if(($client = $this->clients[$connectionUserId] ?? NULL) !== NULL)
		{
			$client->getUser()->setNickName($data);
			$this->send($client->getConnection(), 'changeNickName');
		}
		$this->onlineList();
	}
	
	/**
	 * message
	 *
	 * @param $connectionUserId
	 * @param $data
	 */
	public function message($connectionUserId, $data) : void
	{
		/** @var Client $senderClient */
		if(($senderClient = $this->clients[$connectionUserId] ?? NULL) !== NULL)
		{
			/** @var Client $client */
			foreach($this->clients as $client)
			{
				$this->send($client->getConnection(), 'message', [
					'isSelf'         => $client->getUser()->getId() == $connectionUserId,
					'senderNickName' => $senderClient->getUser()->getNickName(),
					'message'        => $this->sensitiveWord->filter($data),
				]);
			}
		}
	}
	
	/**
	 * online user list
	 */
	public function onlineList() : void
	{
		$list = [];
		/** @var Client $client */
		foreach($this->clients as $client)
		{
			$list[] = [
				'id'       => $client->getUser()->getId(),
				'nickName' => $client->getUser()->getNickName(),
			];
		}
		
		/** @var Client $client */
		foreach($this->clients as $client)
		{
			$this->send($client->getConnection(), 'onlineList', ['list' => $list]);
		}
	}
}
