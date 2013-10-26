<?php

class Cellform_Services_Upload_Core
{
	protected $_files  = array();
	protected $_rulers = array();

	protected $_defaultsdirectory;
	protected $_ismultifile;

	protected $_error_closure;

	public function __construct($fieldname, $defaultsdirectory, array $rules = array())
	{
		$this->_defaultsdirectory = $defaultsdirectory;

		$this->AddRules($rules);
		$this->LoadFile($fieldname);
	}

	public function AddRules(array $rules = array())
	{
		foreach($rules as $rule => $value)
		{
			$class = 'Cellform_Services_Upload_Rulers_' . $rule;

			$this->_rulers[] = Cellform_Injector::Instanciate($class, array($value));
		}
	}

	public function LoadFile($fieldname)
	{
		if (isset($_FILES[$fieldname]))
		{
			$uploadfile = $_FILES[$fieldname];

			$this->_ismultifile = is_array($uploadfile['name']);

			if ($this->IsMultiFiles())
			{
				$this->LoadMultiFiles($fieldname);
			}
			else
			{
				$file = Cellform_Injector::Instanciate('Cellform_Services_Upload_File');

				$file->SetOriginalName($uploadfile['name']);
				$file->SetTempName($uploadfile['tmp_name']);
				$file->SetFilePath($this->_defaultsdirectory);
				$file->SetFieldName($fieldname);
				$file->SetMimeType($uploadfile['type']);
				$file->SetSize($uploadfile['size']);
				$file->SetErrors($uploadfile['error']);

				$this->_files[$fieldname] = $file;
			}
		}
	}

	public function LoadMultiFiles($fieldname)
	{
		$uploadfiles = $_FILES[$fieldname];

		$nbfiles = count($uploadfiles['name']);

		for($i = 0; $i < $nbfiles; $i++)
		{
			$file = Cellform_Injector::Instanciate('Cellform_Services_Upload_File');
	
			$file->SetOriginalName($uploadfiles['name'][$i]);
			$file->SetTempName($uploadfiles['tmp_name'][$i]);
			$file->SetFilePath($this->_defaultsdirectory);
			$file->SetFieldName($fieldname);
			$file->SetMimeType($uploadfiles['type'][$i]);
			$file->SetSize($uploadfiles['size'][$i]);
			$file->SetErrors($uploadfiles['error'][$i]);

			$this->_files[$fieldname . $i] = $file;
		}
	}

	public function IsMultiFiles()
	{
		return $this->_ismultifile;
	}

	public function GetFiles()
	{
		return $this->_files;
	}

	public function HasFiles()
	{
		return count($this->GetFiles()) > 0;
	}

	public function GetUploadedFiles()
	{
		$files = array();

		foreach ($this->GetFiles() as $file)
		{
			if ($file->IsUploaded())
			{
				$files[] = $file;
			}
		}
	
		return $files;
	}

	public function SaveFile(Cellform_Services_Upload_File $file)
	{
		if ($file->GetErrors() != UPLOAD_ERR_OK)
		{
			$file->SetUploaded(false);
			$this->_CallErrorClosure($file, $file->GetErrors());

			return false;
		}

		foreach($this->_rulers as $rule)
		{
			if ($rule->IsValid($file) === false)
			{
				$this->_CallErrorClosure($file, $rule->GetRuleName());

				return false;
			}
		}

		$uploaded = $file->MoveUploadedFile();

		if ($uploaded)
		{
			return true;
		}
		else
		{
			$this->_CallErrorClosure($file, 'NotMoved');

			return false;
		}
	}

	public function Save(\Closure $closure = null)
	{
		$uploaded = null;
		$active_save = true;

		foreach($this->GetFiles() as $file)
		{
			if (!is_null($closure))
			{
				$active_save = $closure($file);
			}

			if (is_null($closure) || (is_null($file->getName()) || $file->getName() == ''))
			{
				$file->SetName($file->GetOriginalName());
			}
			else
			{
				$file->SetName($file->GetName() . '.' . $file->GetOriginalExtension());
			}

			if ($active_save)
			{
				$uploaded = $this->SaveFile($file);
			}
		}

		return $uploaded;
	}

	public function Error(\Closure $closure = null)
	{
		$this->_error_closure = $closure;
	}

	public function Handle(\Closure $closure)
	{
		foreach($this->GetUploadedFiles() as $file)
		{
			$closure($file);
		}
	}

	protected function _CallErrorClosure(Cellform_Services_Upload_File $file, $error)
	{
		if (!is_null($this->_error_closure))
		{
			$error = 'Error_FileUpload' . $error;

			call_user_func($this->_error_closure, $error);
		}
	}
}

?>
