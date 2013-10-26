<?php
/**
 * CellForm Errno Class
 * Management notifications & errors (hight-level & low-level)
 *
 * @author rootofgeno@gmail.com
 * @param Cellform_Injector
 */

class Cellform_Errno
{
	protected $_errno;
	protected $_errnos = array();

    public function __construct()
	{
		$this->_errno = Cellform_Injector::Instanciate('Cellform_Translate', array('Errno'));
	}

	/* ERRNO HIGHT-LEVEL */
	/**
	* Returns error in litteral string.
	*
	* @access public
	* @param string $key
	* @return string
	*/
	public function GetTranslation($key, $args = '')
	{
		return $this->_errno->GetTranslation($key, $args);
	}

	/**
	* Add an translated error in errnos array.
	*
	* @access public
	* @param string $key
	* @return
	*/
	public function AppendError($key, $args = '')
	{
		$this->_errnos['errors'][] = $this->GetTranslation($key, $args);
	}

	/**
	* Add an translated notification in errnos array.
	*
	* @access public
	* @param string $key
	* @return
	*/
	public function AppendNotif($key, $args = '')
	{
		$this->_errnos['notifs'][] = $this->GetTranslation($key, $args);
	}

	/**
	* When key is set as it checks the corresponding error
	* When key is not set as it checks if errors exists
	*
	* @access public
	* @param string $key
	* @return string|false
	*/
	public function ErrorsExist($key = null)
	{
		if ($key)
		{
			return (!empty($this->_errnos['errors'][$key])) ? $this->_errnos['errors'][$key] : false;
		}
		else
		{
			return (!empty($this->_errnos['errors'])) ? true : false;
		}
	}

	/**
	* When key is set as it checks the corresponding notification
	* When key is not set as it checks if notifications exists
	*
	* @access public
	* @param string $key
	* @return string|false
	*/
	public function NotifsExist($key = null)
	{
		if ($key)
		{
			return (!empty($this->_errnos['notifs'][$key])) ? $this->_errnos['notifs'][$key] : false;
		}
		else
		{
			return (!empty($this->_errnos['errors'])) ? true : false;
		}
	}

	/**
	* Returns all errors & notification occurred.
	*
	* @access public
	* @return array
	*/
	public function GetErrnos()
	{
		return $this->_errnos;
	}
	
	/* LOW-LEVEL */
	/**
	* Die like with backtrace.
	*
	* @access public
	* @static
	* @param string $message
	*/
	public static function Error($message)
	{
		$caller = next(debug_backtrace());
		die('ERROR : ' . htmlentities($message) . ' IN FUNCTION : ' . $caller['function']);
	}
}

?>