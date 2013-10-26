<?php
/**
 * CellForm Template Abstraction Class
 *
 * @author rootofgeno@gmail.com
 */

class Cellform_Template
{
	protected $_twig;
	protected $_filepath;

	public function __construct($filepath = ACTIVE_MODULE)
	{
		$this->_filepath = $filepath;

		if (file_exists(TEMPLATE_PATH . $this->_filepath))
		{
			$loader = new Twig_Loader_Filesystem(TEMPLATE_PATH . $this->_filepath);
			$this->_twig = new Twig_Environment($loader, array('cache'	=> ROOT . 'cache'));
		}
	}

	/**
	* Returns a twig object
	*
	* @access public
	* @return object
	*/
	public function GetTwig()
	{
		return $this->_twig;
	}

	/**
	* Returns a compiled template
	*
	* @access public
	* @param string $view
	* @param array $translate
	* @return string
	*/
	public function Render($view, array $args = array())
	{
		$view =  strtolower($view);

		if (file_exists(TEMPLATE_PATH . $this->_filepath . '/' . $view . '.tpl'))
		{
			return $this->_twig->render($view . '.tpl', $args);
		}
		else
		{
			return $this->_twig->render(DEFAULTS_VIEW . '.tpl', $args);
		}
	}
}

?>