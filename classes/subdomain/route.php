<?php defined('SYSPATH') or die('No direct script access.');

class Subdomain_Route extends Kohana_Route {

	public static $default_subdomains = array('');

	/**
	 * Stores a named route and returns it. The "action" will always be set to
	 * "index" if it is not defined.
	 *
	 *     Route::set('default', '(<controller>(/<action>(/<id>)))')
	 *         ->defaults(array(
	 *             'controller' => 'welcome',
	 *         ));
	 *
	 * @param   string   route name
	 * @param   string   URI pattern
	 * @param   array    regex patterns for route keys
	 * @param   array    name of subdomain to apply route rule ( NULL = apply to all subdomains, 'other_string' = unique subdmain to apply )
	 * @return  Route
	 */
	public static function set($name, $uri_callback = NULL, $regex = NULL, array $subdomain = NULL)
	{
		if($subdomain === NULL) {
			$subdomain = self::$default_subdomains;
		}
		
		return Route::$_routes[$name] = new Route($uri_callback, $regex, $subdomain);
	}

	/**
	 * @var  string  route SUBDOMAIN
	 */
	protected $_subdomain;
	
	public function __construct($uri = NULL, $regex = NULL, array $subdomain = NULL) {
		parent::__construct($uri,$regex);
		
		if($subdomain !== NULL) {
			$this->_subdomain = $subdomain;
		}
	}
	
	public function matches($uri, $subdomain = NULL) {
		$subdomain = ($subdomain === NULL) ? Request::$subdomain : $subdomain;
		
		if($subdomain === FALSE) {
			$subdomain = '';
		}
		
		if(in_array('*', $this->_subdomain) || in_array($subdomain, $this->_subdomain)) {
			return parent::matches($uri);
		}
		
		return FALSE;
	}
}