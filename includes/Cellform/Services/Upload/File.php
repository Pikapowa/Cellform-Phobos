<?php

class Cellform_Services_Upload_File extends Cellform_Services_File
{
	protected $_tempname;
	protected $_originalname;
	protected $_fieldname;
	protected $_mimetype;
	protected $_size;
	protected $_errors;
	protected $_isuploaded;

	public function SetTempName($tempname)
	{
		$this->_tempname = $tempname;
	}

	public function GetTempName()
	{
		return $this->_tempname;
	}

	public function SetOriginalName($name)
	{
		$this->_originalname = $name;
	}

	public function GetOriginalName()
	{
		return $this->_originalname;
	}

	public function GetOriginalExtension()
	{
		return pathinfo($this->GetOriginalName(), PATHINFO_EXTENSION);
	}

	public function SetFieldName($fieldname)
	{
		$this->_fieldname = $fieldname;
	}

	public function GetFieldName()
	{
		return $this->_fieldname;
	}

	public function SetMimeType($mimetype)
	{
		$this->_mimetype = $mimetype;
	}

	public function GetMimeType()
	{
		return $this->_mimetype;
	}

	public function SetSize($size)
	{
		if (!is_numeric($size) || $size < 0)
		{
			throw new Exception('Need numeric value !');
		}
		
		$this->_size = $size;
	}

	public function GetSize()
	{
		return $this->_size;
	}

	public function SetUploaded($uploaded)
	{
		$this->_isuploaded = (bool)$uploaded;
	}

	public function IsUploaded()
	{
		return $this->_isuploaded;
	}

	public function SetErrors($code)
	{
		switch ($code)
		{
			case 0:
				$this->_errors = UPLOAD_ERR_OK;
				break;

			case 1:
				$this->_errors = UPLOAD_ERR_INI_SIZE;
				break;

			case 2:
				$this->_errors = UPLOAD_ERR_FORM_SIZE;
				break;

			case 3:
				$this->_errors = UPLOAD_ERR_PARTIAL;
				break;

			case 4:
				$this->_errors = UPLOAD_ERR_NO_FILE;
				break;

			case 6:
				$this->_errors = UPLOAD_ERR_NO_TMP_DIR;
				break;

			case 7:
				$this->_errors = UPLOAD_ERR_CANT_WRITE;
				break;

			case 8:
				$this->_errors = UPLOAD_ERR_EXTENSION;
				break;

			default:
				throw new Exception('Code error invalid : ' . $code);
		}
	}
	
	public function GetErrors()
	{
		return $this->_errors;
	}

	public function MoveUploadedFile()
	{
		$this->SetUploaded(@move_uploaded_file($this->GetTempName(), $this->GetFullQualifiedName()));

		return $this->IsUploaded();
	}
}
 
 ?>
