<?php
/**
 * CellForm HTTP Class
 * Filtering and validation of user data.
 * Management HTTP Protocol.
 *
 * @author rootofgeno@gmail.com
 * @param Cellform_Injector $injector
 * @param Cellform_Errno $errno
 */

class Cellform_HTTP
{
	protected $_headers = array();
	protected $_errors = array();
	protected $_validation_rules = array();
	protected $_filter_rules = array();

	protected $_errno;
	protected $_validation_callback;
	protected $_filter_callback;

	public function __construct(Cellform_Errno $errno)
	{
		$this->_errno = $errno;
		$this->_validation_callback = Cellform_Injector::Instanciate('Cellform_Validation');
		$this->_filter_callback = Cellform_Injector::Instanciate('Cellform_Filter');
	}

	/**
	* Retrieve the client IP address.
	*
	* @access public
	* @return string
	*/
	public function GetIP()
	{
		return $_SERVER['REMOTE_ADDR'];
	}

	/**
	* Check if it's ajax request
	*
	* @access public
	* @return bool
	*/
	public function Is_Set($field)
	{
		return isset($_REQUEST[$field]) ? true : false;
	}

	/**
	* Set cookie with httpOnly flag.
	*
	* @access public
	* @return bool
	*/
	public function AddCookie($name, $value)
	{
		return setCookie($name, $value, TIMECOOKIE, '/', '', false, true);
	}

	/**
	* Unset cookie.
	*
	* @access public
	* @return bool
	*/
	public function DelCookie($name)
	{
		return setCookie($name, '', (time() - 3600), '/');
	}

	/**
	* Get all headers ready to send.
	*
	* @access public
	* @return array
	*/
	public function GetHeaderStatus()
	{
		return $this->_headers;
	}

	/**
	* Set header.
	*
	* @access public
	* @return string|null
	*/
    public function Header($name, $value = null)
	{
		if ($value)
		{
			$this->_headers[$name] = $value;
		}
		else
		{
			return isset($this->_headers[$name]) ? $this->_headers[$name] : null;
		}
	}

	/**
	* Reset all headers.
	*
	* @access public
	*/
	public function ResetHeaders()
	{
		$this->_headers = array();
	}

	/**
	* Send all headers.
	*
	* @access public
	*/
	public function SendHeaders()
	{
		foreach ($this->_headers as $name => $value)
		{
			header($name . ':' . $value);
		}
	}

	/**
	* Send header and gets data formatted.
	*
	* @access public
	* @param string $type
	* @param mixed $data
	* @return array|false|mixed
	*/
	public function GetPostBody($type, $data)
	{
		if (empty($data))
		{
			return false;
		}

		$this->Header('Content-Type', $type . '; charset=UTF-8');
		$this->SendHeaders();

		switch ($type)
		{
			case 'application/json':
			case 'json':
				return json_encode($data);

			case 'text/xml':
			case 'xml':
				return @DOMDocument::loadXML($data) ? : null;

			case 'application/x-www-form-urlencoded':
			case 'text/plain':
			default:
				return $data;
        }
	}

	/**
	* Set validation rules or gets rules.
	*
	* @access public
	* @param array $rules
	* @return array
	*/
	public function ValidatorRules($rules = array())
	{
		if (!empty($rules))
		{
			$this->_validation_rules = $rules;
		}
		else
		{
			return $this->_validation_rules;
		}
	}

	/**
	* Set filtration rules or gets rules.
	*
	* @access public
	* @param array $rules
	* @return array
	*/
	public function FiltratorRules($rules = array())
	{
		if (!empty($rules))
		{
			$this->_filter_rules = $rules;
		}
		else
		{
			return $this->_filter_rules;
		}
	}

	/**
	* Returns the data if they have been validated else returns false.
	*
	* @access public
	* @param array $input
	* @return array|false
	*/
	public function Analyzer($input, $default = 'default')
	{
		$data = $this->Filtrator($input, $this->_filter_rules, $default);
		$validated = $this->Validator($input, $this->_validation_rules);		

		return ($validated !== true) ? false : $data;
	}

	/**
	* Parse ruleset and call validation functions on the array input.
	*
	* @access public
	* @param array $input
	* @param array $ruleset
	* @return array|false
	*/
	public function Validator($input, $ruleset)
	{
		foreach ($ruleset as $field => $rules)
		{
			$rules = explode('|', $rules);

			foreach ($rules as $rule)
			{
				$method = null;
				$param  = null;

				if (strstr($rule, ',') !== false)
				{
					$rule   = explode(',', $rule);
					$method = 'Validate_'.$rule[0];
					$param  = $rule[1];
				}
				else
				{
					$method = 'Validate_'.$rule;
				}

				if (method_exists($this->_validation_callback, $method))
				{
					$result = $this->_validation_callback->$method($field, $input, $param);					

					if (is_array($result))
					{
						$this->_errors[] = $result;
						$this->_errno->AppendError($result['rule']);
					}
				}
				else
				{
					throw new Exception("Validator method '$method' does not exist.");
				}
			}
		}

		return (count($this->_errors) > 0) ? $this->_errors : true;
	}

	/**
	* Parse filterset and call filtration functions on the array input.
	*
	* @access public
	* @param array $input
	* @param array $filterset
	* @return array|false
	*/
	public function Filtrator($input, $filterset, $default = 'default')
	{
		foreach ($filterset as $field => $filters)
		{
			if (!array_key_exists($field, $input))
			{
				$input[$field] = $default;
				continue;
			}

			$filters = explode('|', $filters);

			foreach ($filters as $filter)
			{
				$params = null;

				if(strstr($filter, ',') !== false)
				{
					$filter = explode(',', $filter);
					$params = array_slice($filter, 1, count($filter) - 1);
					$method = 'Filter_' . $filter[0];
				}
				else
				{
					$method = 'Filter_'.$filter;
				}

				if(method_exists($this->_filter_callback, $method))
				{
					$input[$field] = $this->_filter_callback->$method($input[$field], $params);
				}
				else
				{
					throw new Exception("Filtrator method '$filter' does not exist.");
				}
			}
		}

		return $input;
	}

	/**
	* Sanitize the array input with specific fields or not.
	*
	* @access public
	* @param array $input
	* @return array
	*/
	public function Sanitizer($input, $fields = null)
	{	
		if(is_null($fields))
		{
			$fields = array_keys($input);
		}

		foreach($fields as $field)
		{
			if(!isset($input[$field]))
			{
				continue;
			}
			else
			{
				$value = trim($input[$field]); 

				if(is_string($value))
				{
					$value = filter_var($value, FILTER_SANITIZE_STRING);		
				}

				$input[$field] = $value;
			}
		}

		return $input;
	}

	/**
	* Returns true if data is valid or false if error is occured (MODE BOOLEAN)
	*
	* @access public
	* @return bool
	*/
	public function IsValid()
	{
		return empty($this->_errors) ? true : false;
	}

	/**
	* Get global errno message, print in JSON format if specified & if not errors is occurred true is returned.
	* Caution : When $json is true, twig become inactive.
	*
	* @access public
	* @return bool
	*/
	public function CheckErrnos($json = true)
	{
		$errnos = $this->_errno->GetErrnos();

		if ($json === true)
		{
			echo $this->GetPostBody('application/json', $errnos);
		}

		return empty($errnos['errors']) ? true : false;
	}
}

?>