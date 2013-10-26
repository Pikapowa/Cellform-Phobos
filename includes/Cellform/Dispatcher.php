<?php
/**
 * CellForm Dispatcher Class & Router parents
 *
 * @author rootofgeno@gmail.com
 */

class Cellform_Dispatcher extends Cellform_Router
{
	protected $_controller = false;

	public function __construct()
	{
		//parent::__construct();
	}

	/**
	* Return a module object controller
	*
	* @access public
	* @param string $name
	* @return object|false
	*/
	public function LoadController($name)
	{
		$file = MODULES_PATH . $name . DIRECTORY_SEPARATOR . 'Controller.php';
		$class = $name . '_' . 'Controller';

		if (file_exists($file))
		{
			require_once($file);
			$this->_controller = Cellform_Injector::Instanciate($class);

			if (!method_exists($this->_controller, 'GetName') || !is_callable(array($this->_controller, 'GetName')))
			{
				throw new Exception('Module Controller ' . $name . ' need public function GetName()');
			}

			return $this->_controller;
		}
		else
		{
			return false;
		}
	}

	/**
	* Dispatch in /module/component/action/params & call the action
	*
	* @access public
	* @param string $module
	* @param string $component
	* @param string $action
	* @return mixed
	*/
	public function Dispatcher($component, $action, array $filters = array())
	{
		if ($this->_controller)
		{
			$file = MODULES_PATH . $this->_controller->GetName() . DIRECTORY_SEPARATOR . $component . '.php';
			$class = $this->_controller->GetName() . '_' . $component;

			if (file_exists($file))
			{
				require_once($file);

				if (method_exists($class, $action) && is_callable(array($class, $action)))
				{
					$component = Cellform_Injector::Instanciate($class, array($this->_controller));

					return call_user_func(array($component, $action));
				}
			}
		}
	}

	/**
	* Return the module controller
	*
	* @access public
	* @return object|false
	*/
	public function GetController()
	{
		return ($this->_controller) ? $this->_controller : false;
	}
}
?>