<?php
class Curl {
	private $headers;
	private $user_agent;
	private $cookie;
	private $referer;

	function __construct()
	{
		$this->referer = $_SERVER["SERVER_NAME"];
	}
	function cURL($url='') {
		$this->setUserAgent('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)');
		if (empty($url)) {
			$this->setReferer($url);
		}
	}

	function get($url='') {
		if (!empty($url)) {
			$ch = curl_init($url);
			curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt ($ch, CURLOPT_HEADER, 0);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_USERAGENT, $this->user_agent);
			curl_setopt ($ch, CURLOPT_REFERER, $this->referer);
			if (!empty($this->cookie)) {
				curl_setopt ($ch, CURLOPT_COOKIEFILE, $this->cookie);
				curl_setopt ($ch, CURLOPT_COOKIEJAR, $this->cookie);
			}
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
			$res = curl_exec($ch);
			curl_close($ch);
			return $res;
		}
	}

	function post($url='',$data) {
		if (!empty($url)) {
			if (is_array($data)) {
				$data=$this->postEncode($data);
			}
			$ch = curl_init($url);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt ($ch, CURLOPT_HEADER, 0);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_USERAGENT, $this->user_agent);
			curl_setopt ($ch, CURLOPT_REFERER, $this->referer);
			curl_setopt ($ch, CURLOPT_POST, 1);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
			if (!empty($this->cookie)) {
				curl_setopt ($ch, CURLOPT_COOKIEFILE, $this->cookie);
				curl_setopt ($ch, CURLOPT_COOKIEJAR, $this->cookie);
			}
			$res = curl_exec($ch);
			curl_close($ch);
			return $res;
		}
	}

	function postEncode($varArray) {
		$args = array();
		foreach($varArray as $key => $value){
			array_push($args,$key.'='.urlencode($value));
		}
		return implode('&',$args);
	}

	function setUserAgent($user_agent='') {
		if (!empty($user_agent)) {
			$this->user_agent=$user_agent;
		}
	}

	function setCookie($cookie_file='') {
		if (!empty($cookie_file)) {
			$this->cookie=$cookie_file;
		}
	}

	function setReferer($referer='') {
		if (!empty($referer)) {
			$this->referer=$referer;
		}
	}
}