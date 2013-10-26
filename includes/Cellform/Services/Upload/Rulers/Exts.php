<?php

class Cellform_Services_Upload_Rulers_Exts extends Cellform_Services_Upload_Rulers
{
	protected $_exts = array();

	public function Initialize()
	{
		$this->SetExtensions($this->_rule);
	}

	public function GetRuleName()
	{
		return 'Exts';
	}

	public function SetExtensions($exts)
	{
		if (!is_array($exts))
		{
			throw new Exception('The extensions rules need array value.');
		}

		$this->_exts = $exts;
	}

	public function GetExtensions()
	{
		return $this->_exts;
	}

	public function IsValid(Cellform_Services_Upload_File $file)
	{
		if (!in_array($file->GetExtension(), $this->GetExtensions()))
		{
			return false;
		}
	}
}

?>
