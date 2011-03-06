<?php

class Route extends Kohana_Route {

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
	 * @param   string   name of subdomain to apply route rule ( NULL = default, '*' = apply to all subdomains, 'other_string' = unique subdmain to apply )
	 * @return  Route
	 */
	public static function set($name, $uri_callback = NULL, $regex = NULL, $subdomain = NULL)
	{
		return Route::$_routes[$name] = new Route($uri_callback, $regex, $subdomain);
	}

	/**
	 * @var  string  route SUBDOMAIN
	 */
	protected $_subdomain = '';
	
	public function __construct($uri = NULL, $regex = NULL, $subdomain = NULL) {
		if(!empty($subdomain)) {
			$this->_subdomain = $subdomain;
		}
		
		parent::__construct($uri,$regex);
	}
}