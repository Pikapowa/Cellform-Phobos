<?php

class Cellform_Services_Upload_Rulers_Size extends Cellform_Services_Upload_Rulers
{
	protected $_size;

	public function Initialize()
	{
		$this->SetSize($this->_rule);
	}

	public function GetRuleName()
	{
		return 'Size';
	}

	public function SetSize($size)
	{
		if (!is_numeric($size) || $size <= 0)
		{
			throw new Exception('The size rules need numeric value (superior to 0).');
		}

		$this->_size = $size;
	}

	public function GetSize()
	{
		return $this->_size;
	}

	public function IsValid(Cellform_Services_Upload_File $file)
	{
		if ($file->GetSize() >= $this->GetSize())
		{
			return false;
		}
	}
}

?>
