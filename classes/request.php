<?php

class Request extends Kohana_Request {

	/**
	 * @var  string  request Subdomain
	 */
	public static $subdomain;
	
	public static function factory($uri = TRUE, Cache $cache = NULL)	{
		if(!Kohana::$is_cli) {
			self::$subdomain = Request::detect_subdomain();
		}
		
		return parent::factory($uri,$cache);
	}
	
	public static function detect_subdomain($base = NULL, $host = NULL) {
		if($base === NULL) {
			$base = parse_url(Kohana::$base_url, PHP_URL_HOST);
		}
		
		if($host === NULL) {
			$host = $_SERVER['HTTP_HOST'];
		}
		
		if(empty($base) || preg_match('/^(localhost|test|example|invalid)$/',$host) || Valid::ip($host)) {
			return FALSE;
		}
		
		$sub_pos = ((int)strpos($host,$base))-1;
		
		if($sub_pos > 0) {
			$subdomain = substr($host,0,$sub_pos);
			
			if(!empty($subdomain)) {
				return $subdomain;
			}
		}
		
		return FALSE;
	}	
	
	/**
	 * Process URI
	 *
	 * @param   string  $uri     URI
	 * @param   array   $routes  Route
	 * @return  array
	 */
	public static function process_uri($uri, $routes = NULL, $subdomain = NULL)
	{
		// Load routes
		$routes = ($routes === NULL) ? Route::all() : $routes;
		$params = NULL;
		$subdomain = ($subdomain === NULL) ? Request::$subdomain : $subdomain;
		
		foreach ($routes as $name => $route)
		{
			// We found something suitable
			if ($params = $route->matches($uri, $subdomain))
			{
				if ( ! isset($params['uri']))
				{
					$params['uri'] = $uri;
				}

				if ( ! isset($params['route']))
				{
					$params['route'] = $route;
				}

				break;
			}
		}

		return $params;
	}	
}