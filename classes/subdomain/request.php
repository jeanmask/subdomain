<?php defined('SYSPATH') or die('No direct script access.');

class Subdomain_Request extends Kohana_Request {

	/**
	 * @var  string  request Subdomain
	 */
	public static $subdomain ;
			
	public static function factory($uri = TRUE, $client_params = array(), $allow_external = TRUE, $injected_routes = array()){
		self::$subdomain = Request::catch_subdomain() ;

		return parent::factory($uri, $client_params, $allow_external, $injected_routes) ;
	}
	
	public static function catch_subdomain($base_url = NULL, $host = NULL) {
		if($base_url === NULL) {
			$base_url = parse_url(Kohana::$base_url, PHP_URL_HOST) ;
		}
		
		if($host === NULL) {
			// if( Kohana::$is_cli ) {
			// 	return FALSE ;
			// }
			
			$host = $_SERVER['HTTP_HOST'] ;
		}
		
		if(empty($base_url) || empty($host) || in_array($host, Route::$localhosts) || Valid::ip($host)) {
			return FALSE ;
		}
		
		$sub_pos = (int)strpos($host, $base_url) - 1 ;
		
		if($sub_pos > 0) {
			$subdomain = substr($host,0,$sub_pos) ;
			
			if( !empty($subdomain) ) {
				return $subdomain ;
			}
		}
		
		return Route::SUBDOMAIN_EMPTY ;
	}
}
