<?php

class Request extends Kohana_Request {

	public static $default_subdomain = NULL;
	
	public static function detect_subdomain($base = NULL, $host = NULL) {
		if($base === NULL) {
			$base = parse_url(Kohana::$base_url, PHP_URL_HOST);
		}
		
		if($host === NULL) {
			$host = $_SERVER['HTTP_HOST'];
		}
		
		if(empty($base) || preg_match('/^(localhost|test|example|invalid)/',$host) || Valid::ip($host)) {
			return self::$default_subdomain;
		}
		
		$sub_pos = ((int)strpos($host,$base))-1;
		
		if($sub_pos > 0) {
			$subdomain = substr($host,0,$sub_pos);
			
			if(!empty($subdomain)) {
				return $subdomain;
			}
		}
		
		return self::$default_subdomain;
	}
	
	/**
	 * @var  string  request Subdomain
	 */
	protected $_subdomain;
	
	public function __construct($uri, Cache $cache = NULL) {
		parent::__construct($uri, $cache);
		
		if(!Kohana::$is_cli) {
			$this->_subdomain = Request::detect_subdomain();
		}
	}
}