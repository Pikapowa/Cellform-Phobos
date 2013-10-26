<?php

class Cellform_Services_Uploader
{
	protected $_rules = array();

	public function __construct(array $rules = array())
	{
		if (empty($rules['files']))
		{
			throw new Exception('No specified files in rules config.');
		}

		$this->_rules = $rules;
	}

	public function Upload($fieldname, $directory)
	{
		if (!is_array($this->_rules['files'][$fieldname]))
		{
			throw new Exception('Unknown file name, check your rules config.');
		}

		$rules = $this->_rules['files'][$fieldname];

		if (!empty($_FILES[$fieldname]['name']))
		{
			return Cellform_Injector::Instanciate('Cellform_Services_Upload_Core', array($fieldname, $directory, $rules));
		}
		else
		{
			return false;
		}
	}
}

?>