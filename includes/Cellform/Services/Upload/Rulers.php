<?php

abstract class Cellform_Services_Upload_Rulers
{
	protected $_rule;

	abstract public function Initialize();
	abstract public function GetRuleName();
	abstract public function IsValid(Cellform_Services_Upload_File $file);

	public function __construct($rule)
	{
		if (empty($rule))
		{
			throw new Exception('The rulers need arguments.');
		}

		$this->_rule = $rule;

		$this->Initialize();
	}
}

?>
