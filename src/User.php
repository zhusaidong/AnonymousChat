<?php
/**
 * user
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace Zhusaidong\AnonymousChat;

class User
{
	/**
	 * @var string $id
	 */
	private $id = '';
	/**
	 * @var string $nickName
	 */
	private $nickName = '';
	
	/**
	 * @return string
	 */
	public function getId() : string
	{
		return $this->id;
	}
	
	/**
	 * @param string $id
	 *
	 * @return User
	 */
	public function setId(string $id) : User
	{
		$this->id = $id;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getNickName() : string
	{
		return $this->nickName;
	}
	
	/**
	 * @param string $nickName
	 *
	 * @return User
	 */
	public function setNickName(string $nickName) : User
	{
		$this->nickName = $nickName;
		
		return $this;
	}
	
	/**
	 * toString
	 *
	 * @return string
	 */
	public function __toString() : string
	{
		return $this->nickName . '[' . $this->id . ']';
	}
}
