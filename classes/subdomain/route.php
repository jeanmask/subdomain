<?php defined('SYSPATH') or die('No direct script access.');

class Subdomain_Route extends Kohana_Route {

    const SUBDOMAIN_WILDCARD = '*', SUBDOMAIN_EMPTY = '' ;

	public static $default_subdomains = array(self::SUBDOMAIN_EMPTY, 'www');

	/**
	 * @var  string  route SUBDOMAIN
	 */
	protected $_subdomain;
	
	public function __construct($uri = NULL, $regex = NULL) {
		parent::__construct($uri, $regex);
		
        // Set default subdomains in this route rule
		$this->_subdomain = self::$default_subdomains;
	}


    /**
     * Set one or more subdomains to execute this route
     *
	 *     Route::set('default', '(<controller>(/<action>(/<id>)))')
     *         ->subdomains(array(Route::SUBDOMAIN_EMPTY, 'www1', 'foo', 'bar'))
	 *         ->defaults(array(
	 *             'controller' => 'welcome',
	 *         ));
     *
	 * @param   array    name(s) of subdomain(s) to apply in route
     * @return Route
     */      
    public function subdomains(array $name) {
        $this->_subdomain = $name;

        return $this;
    }
	
	public function matches(Request $request, $subdomain = NULL) {
		$subdomain = (!isset($subdomain) OR $subdomain === NULL) ? Request::$subdomain : $subdomain;
		
		if($subdomain === FALSE) 
		{
			$subdomain = self::SUBDOMAIN_EMPTY;
		}
		
		if(in_array(self::SUBDOMAIN_WILDCARD, $this->_subdomain) OR in_array($subdomain, $this->_subdomain)) 
		{
			return parent::matches($request) ;
		}
		
		return FALSE;
	}
}
