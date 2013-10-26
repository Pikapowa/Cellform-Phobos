<?php

class Cellform_Services_File
{
	protected $_name;
	protected $_path;

	public function SetName($name)
	{
		$this->_name = $name;
	}

	public function GetName()
	{
		return $this->_name;
	}

	public function SetFilePath($path, $mode = '')
	{
		$path = ROOT . $path;

		if (!is_dir($path))
		{
			throw new Exception($path . 'is not a directory.');
		}

		if ($mode == 'w')
		{
			if (!is_writable($path))
			{
				throw new Exception('Specified directory ' . $path . ' is not writable.');
			}
		}
		else if ($mode == 'r')
		{
			if (!is_readable($path))
			{
				throw new Exception('Specified directory ' . $path . ' is not readable.');
			}
		}
		else
		{
			if (!is_writable($path) && !is_readable($path))
			{
				throw new Exception('Specified directory ' . $path . ' is not readable & writable.');
			}
		}

		$this->_path = $path;
	}

	public function GetFilePath()
	{
		return $this->_path;
	}

	public function GetFullQualifiedName()
	{
		return $this->_path . $this->_name;
	}

	public function GetExtension()
	{
		return pathinfo($this->GetName(), PATHINFO_EXTENSION);
	}

	public function Delete()
	{
		return unlink_evolved($this->GetFullQualifiedName());
	}

	public function Write($data, $flags = null)
	{
		if (($oct = file_put_contents($this->GetFullQualifiedName(), $data)) === false)
		{
			throw new Exception('Error writing or opening file : ' . $this->GetFullQualifiedName());
		}

		return $oct;
	}

	public function Read()
	{
		if (($data = file_get_contents($this->GetFullQualifiedName())) === false)
		{
			throw new Exception('Error reading or opening file : ' . $this->GetFullQualifiedName());
		}

		return $data;
	}

}
 
 ?>
