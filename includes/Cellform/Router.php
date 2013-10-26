<?php
/**
 * CellForm Router Class
 * Redirect urls to /modules/components/action/param/param/param
 * Tags :
 * Module, Component & Action routing -> (?#module#), (?#component#), (?#action#) for specified the type field.
 * Parameters routing -> (?#params#) with {params} the name of parameters.
 *
 * Example :
 * Cellform_Router::AddRoute('signin/(?#email#)/(?#password#)', 'loginbox', 'defaults', 'signin');
 * Translate /loginbox/defaults/signin/email/password to /signin/email/password
 *
 * @author rootofgeno@gmail.com
 */

class Cellform_Router
{
	public static $routes = array();
	public static $filter = '/([^\/]+)';
	public static $language;

	public function __construct()
	{
	}

	/**
	* Apply a regex filter on each slashed section
	*
	* @access public
	* @param string $route
	* @return string
	*/
	public static function AddFilter($route)
	{
		return str_replace('/', self::$filter, $route);
	}

	/**
	* Apply the reverse regex filter
	*
	* @access public
	* @param string $route
	* @return string
	*/
	public static function DelFilter($route)
	{
		return str_replace(self::$filter, '/', $route);
	}

	/**
	* Add a route
	*
	* @access public
	* @param string $route
	* @return array
	*/
	public static function AddRoute($route, $module = '', $component = '', $action = '', array $params = array())
	{
		$route = self::AddFilter($route);

		return self::$routes[] = array(
			'route'		=> $route,
			'module'	=> $module,
			'component' => $component,
			'action'	=> $action,
			'params'	=> $params,
		);
	}

	/**
	* Match an specified route regex with url given
	*
	* @access public
	* @param int $id
	* @param string $url
	* @return string|false
	*/
	public static function MatchUrl($id, $url)
	{
		if (preg_match('`^' . self::$routes[$id]['route'] . '$`', $url, $matches))
		{
			return $matches;
		}
		else
		{
			return false;
		}
	}

	/**
	* Parse regex url & merge with routing rules
	*
	* @access public
	* @param int $id
	* @param array $url
	* @return
	*/
	public static function ParseUrl($id, array $url = array())
	{
		$params = array();
		$route 	= self::DelFilter(self::$routes[$id]['route']);
		$route 	= explode('/', $route);

		for($i = 0; $i < count($route); $i++)
		{
			if (strstr($route[$i], '#module#'))
			{
				self::$routes[$i]['module'] = $url[$i];
			}

			if (strstr($route[$i], '#component#'))
			{
				self::$routes[$id]['component'] = $url[$i];
			}

			else if (strstr($route[$i], '#action#'))
			{
				self::$routes[$id]['action'] = $url[$i];
			}

			else
			{
				if (strpos($route[$i], '#'))
				{
					$p = explode('#', $route[$i]);
					self::$routes[$id]['params'][$p[1]] = $url[$i];
				}
			}
		}
	}

	/**
	* Initialize the router engine (Must be called in the headers of index.php)
	*
	* @access public
	* @param string $url
	* @return array
	*/
	public static function RequestRoute($url)
	{
		$url = rtrim($url, '/');
		$tab = explode('/', $url);

		self::SelectLanguage($tab);

		$url = implode('/', $tab);

		for($i = 0; $i < count(self::$routes); $i++)
		{
			if (self::MatchUrl($i, $url) !== false)
			{
				self::ParseUrl($i, $tab);

				if (!empty(self::$routes[$i]['params']))
				{
					$_GET = array_merge($_GET, self::$routes[$i]['params']);
				}

				return self::$routes[$i];
			}
		}

		return self::NoRoute($tab);
	}

	/**
	* Analyze url array & language variable in $_SESSION & finally check for language request.
	* Warning : This section must be deleted with array_splice before MatchUrl() call.
	*
	* @access public
	* @param array & $url
	*/
	public static function SelectLanguage(array & $url)
	{
		if (!empty($url[0]) && file_exists(LANGUAGE_PATH . $url[0] . DIRECTORY_SEPARATOR))
		{
			$lang = $url[0];
			array_splice($url, 0, 1);

			self::$language = $lang;
		}
		else
		{
			if (isset($_SESSION['lang']))
			{
				self::$language = $_SESSION['lang'];	// Will checked by translate class
			}
		}
	}

	/**
	* Get current language choice by user through the router
	*
	* @access public
	* @return string
	*/
	public static function GetLanguage()
	{
		return !empty(self::$language) ? self::$language : DEFAULTS_LANG;
	}

	/**
	* If no route, return raw url & if empty return defaults values
	*
	* @access public
	* @param array $url
	* @return array
	*/
	public static function NoRoute(array $url)
	{
		return array(
			'module'	=> !empty($url[0]) ? ucfirst(strtolower($url[0])) : DEFAULTS_MODULE,
			'component'	=> !empty($url[1]) ? ucfirst(strtolower($url[1])) : DEFAULTS_COMPONENT,
			'action'	=> !empty($url[2]) ? ucfirst(strtolower($url[2])) : DEFAULTS_ACTION,
		);
	}

	/**
	* Get a route (after handling or not)
	*
	* @access public
	* @param int $id
	* @return array
	*/
	public static function GetRoute($id)
	{
		return self::$routes[$id];
	}
}

?>