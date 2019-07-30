<?php
/**
 * Sensitive Word
 *
 * @author zhusaidong [zhusaidong@gmail.com]
 */

namespace Zhusaidong\AnonymousChat;

class SensitiveWord
{
	/**
	 * @var string $banPath ban path
	 */
	private $banPath = 'ban/';
	/**
	 * @var array $banTexts
	 */
	private $banTexts = [];
	
	/**
	 * SensitiveWord constructor.
	 */
	public function __construct()
	{
		$this->load();
	}
	
	/**
	 * load ban text
	 */
	private function load() : void
	{
		array_map(function($filePath)
		{
			$this->banTexts = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		}, glob($this->banPath . '*.txt'));
	}
	
	/**
	 * filter
	 *
	 * @param string $data
	 *
	 * @return string
	 */
	public function filter(string $data) : string
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
	public function __toString() : string
	{
		return implode(PHP_EOL, $this->banTexts);
	}
}
