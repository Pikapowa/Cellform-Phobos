<?php
/**
 * Cellform Video Services Class
 * Videos services class must be implements interface iVideo & extends with Cellform_Services_Video
 * 
 *
 * @author rootofgeno@gmail.com
 */

class Cellform_Services_Video
{
	protected $_class = '';
	protected $_regex = array(
		'Youtube'		=> '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com/)(.*?)$#x',
		'Rutube'		=> '#^(?:https?://)?(?:www\.)?(?:rutube\.ru/)(.*?)$#x',
		'Dailymotion'	=> '#^(?:https?://)?(?:www\.)?(?:dailymotion\.com/)(.*?)$#x',
		'Wat'			=> '#^(?:https?://)?(?:www\.)?(?:wat\.tv/)(.*?)$#x',
		'Vimeo'			=> '#^(?:https?://)?(?:www\.)?(?:vimeo\.com/)(.*?)$#x',
	);

	/**
	* Load url & detect video service, finally switch the specific class
	*
	* @access public
	* @param string $url
	* @return bool
	*/
	public function Load($url)
    {
		if (!is_string($url))
        {
			return false;
        }

		foreach($this->_regex as $key => $value)
		{
			if (preg_match($value, $url) === 1)
			{
				$this->_class = 'Cellform_Services_Video_' . $key;
			}
		}

		if (!empty($this->_class))
		{
			$this->object = Cellform_Injector::Instanciate($this->_class, array($url));

			return true;
		}

		return false;
	}

	/**
	* Return the name of video service
	*
	* @access public
	* @return string|false
	*/
	public function GetVideoService()
	{
		return ($this->object) ? $this->object->GetVideoService() : false;
	}

	/**
	* Return embed video link for mobile devices
	*
	* @access public
	* @return string|false
	*/
	public function GetVideoMobile()
	{
		return ($this->object) ? $this->object->GetVideoMobile() : false;
	}

	/**
	* Return uniq id video
	*
	* @access public
	* @return string|false
	*/
	public function GetVideo()
	{
		return ($this->object) ? $this->object->GetVideo() : false;
	}

	/**
	* Recover image video presentation
	*
	* @access public
	* @return string|bool
	*/
	public function GetThumbnail()
	{
		return ($this->object) ? $this->object->GetThumbnail() : false;
	}
}

?>