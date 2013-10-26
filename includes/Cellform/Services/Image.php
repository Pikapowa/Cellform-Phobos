<?php
/**
 * Cellform Image manipulate Services Class
 * For image transforming
 * 
 *
 * @author rootofgeno@gmail.com
 */

class Cellform_Services_Image
{
	protected $_handle;
	protected $_mimetype;

	public function __construct($filepath)
	{
		$this->Load($filepath);
	}

	public function Load($filepath)
	{
		$this->_source = $filepath;

		list($width, $height, $type) = getimagesize($this->_source);

		if ($width == 0 && $height == 0)
		{
			throw new Exception('This is not a valid image file.');
		}

		$this->_mimetype = $type;

		switch($this->_mimetype)
		{
			case IMAGETYPE_JPEG:
				$this->_handle = imagecreatefromjpeg($this->_source);
				break;
		
			case IMAGETYPE_GIF:
				$this->_handle = imagecreatefromgif($this->_source);
				break;
		
			case IMAGETYPE_PNG:
				$this->_handle = imagecreatefrompng($this->_source);
				break;
		
			default:
				throw new Exception('Picture format not supported');
		}
	}

	public function Save($filepath)
	{
		$filepath = ROOT . $filepath;

		switch($this->_mimetype)
		{
			case IMAGETYPE_JPEG:
				return imagejpeg($this->_handle, $filepath);

			case IMAGETYPE_GIF:
				return imagegif($this->_handle, $filepath);

			case IMAGETYPE_PNG:
				return imagepng($this->_handle, $filepath);
		}
	}

	public function GetWidth()
	{
		return imagesx($this->_handle);
	}

	public function GetHeight()
	{
		return imagesy($this->_handle);
	}

	public function GetRatio()
	{
		return $this->GetWidth() / $this->GetHeight();
	}

	public function ResizeToHeight($height)
	{
		$ratio = $height / $this->GetHeight();
		$width = $this->GetWidth() * $ratio;
		$this->Resize($width,$height);
	}

	public function ResizeToWidth($width)
	{
		$ratio = $width / $this->GetWidth();
		$height = $this->Getheight() * $ratio;
		$this->Resize($width,$height);
	}

	public function Scale($scale)
	{
		$width = $this->GetWidth() * $scale/100;
		$height = $this->GetHeight() * $scale/100;
		$this->Resize($width,$height);
	}

	public function Resize($width, $height)
	{
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->_handle, 0, 0, 0, 0, $width, $height, $this->GetWidth(), $this->GetHeight());
		$this->_handle = $new_image;
	}

	public function MaxRatio($ratio)
	{
		return (($this->GetRatio() > $ratio));
	}

	public function MaxSize($width, $height)
	{
		return (($this->GetWidth() <= $width) && ($this->GetHeight() <= $height));
	}

	public function MinSize($width, $height)
	{
		return (($this->GetWidth() >= $width) && ($this->GetHeight() >= $height));
	}
}

?>