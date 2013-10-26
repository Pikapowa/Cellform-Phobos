<?php
/**
 * CellForm Validator Class
 * Callback function for validation ruleset
 *
 * @author rootofgeno@gmail.com
 */

class Cellform_Validation
{
	public function Validate_Csrf($field, $input, $param = null)
	{
		if (!isset($input[$field]))
		{
			return true;
		}

		if (strcmp($input[$field], base64_encode(session_id())) !== 0)
		{
			return array(
					'field' => $field,
					'value' => $input[$field],
					'rule'	=> __function__,
					'param' => $param
			);
		}
	}
	/**
	* Test the minimum lenght of field.
	*
	* @access public
	* @param string $field
	* @param array $input
	* @return true|array
	*/
	public function Validate_MinLen($field, $input, $param = null)
	{
		if (!isset($input[$field]))
		{
			return true;
		}

		if (function_exists('mb_strlen'))
		{
			if (mb_strlen($input[$field]) >= (int)$param)
			{
				return true;
			}
		}
		else
		{
			if (strlen($input[$field]) >= (int)$param)
			{
				return true;
			}
		}

		return array(
			'field' => $field,
			'value' => $input[$field],
			'rule'	=> __function__,
			'param' => $param			
		);
	}

	/**
	* Test if the field exist.
	*
	* @access public
	* @param string $field
	* @param array $input
	* @return true|array
	*/
	public function Validate_Required($field, $input, $param = null)
	{
		if (isset($input[$field]) && trim($input[$field]) != '')
		{
			return true;
		}
		else
		{
			return array(
				'field' => $field,
				'value' => null,
				'rule'	=> __function__,
				'param' => $param
			);
		}
	}

	/**
	* Test if the field is a valid email.
	*
	* @access public
	* @param string $field
	* @param array $input
	* @return true|array
	*/
	public function Validate_Email($field, $input, $param = null)
	{
		if (!isset($input[$field]) || empty($input[$field]))
		{
			return true;
		}

		if (!filter_var($input[$field], FILTER_VALIDATE_EMAIL))
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __function__,
				'param' => $param
			);
		}
	}

	/**
	* Test the maximum lenght of field.
	*
	* @access public
	* @param string $field
	* @param array $input
	* @return true|array
	*/
	public function Validate_MaxLen($field, $input, $param = null)
	{
		if (!isset($input[$field]))
		{
			return true;
		}

		if (function_exists('mb_strlen'))
		{
			if (mb_strlen($input[$field]) <= (int)$param)
			{
				return true;
			}
		}
		else
		{
			if (strlen($input[$field]) <= (int)$param)
			{
				return true;
			}
		}

		return array(
			'field' => $field,
			'value' => $input[$field],
			'rule'	=> __function__,
			'param' => $param
		);
	}

	/**
	* Test if the field is a valid alphanumeric string.
	*
	* @access public
	* @param string $field
	* @param array $input
	* @return true|array
	*/
	public function Validate_AlphaNumeric($field, $input, $param = null)
	{	
		if (!isset($input[$field]) || empty($input[$field]))
		{
			return true;
		}

		if (!preg_match("/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i", $input[$field]) !== false)
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __function__,
				'param' => $param	
			);
		}
	}

	/**
	* Test if the field is an integer.
	*
	* @access public
	* @param string $field
	* @param array $input
	* @return true|array
	*/
	public function Validate_Numeric($field, $input, $param = null)
	{	
		if (!isset($input[$field]) || empty($input[$field]))
		{
			return true;
		}

		if (!preg_match("/^([0-9])+$/i", $input[$field]) !== false)
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __function__,
				'param' => $param	
			);
		}
	}

   /**
	* Test if the field is a boolean.
	*
	* @access public
	* @param string $field
	* @param array $input
	* @return true|array
	*/
	public function Validate_Bool($field, $input, $param = null)
	{
		if (!isset($input[$field]) || empty($input[$field]))
		{
			return true;
		}
	
		if ($input[$field] !== 'true' && $input[$field] !== 'false')
		{
			return array(
					'field' => $field,
					'value' => $input[$field],
					'rule'	=> __function__,
					'param' => $param
			);
		}
	}

    /**
	* Test if the field is equal to $param string.
	*
	* @access public
	* @param string $field
	* @param array $input
	* @return true|array
	*/
	public function Validate_Captcha($field, $input, $param = null)
	{
		if (!isset($input[$field]) || empty($input[$field]))
		{
			return true;
		}

		if (strcasecmp($input[$field], (string)$param) !== 0)
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __function__,
				'param' => $param	
			);
		}
	}

    /**
	* Test if the field is equal to $param field value .
	*
	* @access public
	* @param string $field
	* @param array $input
	* @return true|array
	*/
	public function Validate_Strcmp($field, $input, $param = null)
	{
		if (!isset($input[$field]) || empty($input[$field]))
		{
			return true;
		}

		if (strcmp($input[$field], $input[$param]) !== 0)
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __function__,
				'param' => $param	
			);
		}
	}

	/**
	* Match the url format.
	*
	* @access public
	* @param string $field
	* @param array $input
	* @return true|array
	*/
	public function Validate_Url($field, $input, $param = null)
	{
		if (!isset($input[$field]) || empty($input[$field]))
		{
			return true;
		}

		if(!filter_var($input[$field], FILTER_VALIDATE_URL))
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __function__,
				'param' => $param	
			);
		}
	}

	/**
	* Check for filename safety
	*
	* @access public
	* @param string $field
	* @param array $input
	* @return true|array
	*/
	public function Validate_Filename($field, $input, $param = null)
	{
		if (!isset($input[$field]) || empty($input[$field]))
		{
			return true;
		}

		if (strpos($input[$field], '/') !== false)
		{
			return array(
				'field' => $field,
				'value' => $input[$field],
				'rule'	=> __function__,
				'param' => $param	
			);
		}
	}
}

?>