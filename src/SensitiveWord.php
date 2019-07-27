<?php
/**
 * Sensitive Word
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace Zhusaidong\AnonymousChat;

class SensitiveWord
{
	private $banPath  = 'ban/';
	private $banTexts = [];
	
	public function __construct()
	{
		$this->load();
	}
	
	private function load()
	{
		array_map(function($filePath)
		{
			$this->banTexts = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		}, glob($this->banPath . '*.txt'));
	}
	
	public function filter($data)
	{
		if(preg_match_all("/" . implode("|", $this->banTexts) . "/i", $data, $matches))
		{
			$replaces = [];
			foreach($matches[0] as $pattern)
			{
				$replaces[$pattern] = str_pad('', mb_strlen($pattern), '*');
			}
			
			$data = strtr($data, $replaces);
		}
		
		return $data;
	}
	
	/**
	 * toString
	 *
	 * @return string
	 */
	public function __toString()
	{
		return implode(PHP_EOL, $this->banTexts);
	}
}
