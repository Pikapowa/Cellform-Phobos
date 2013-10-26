<?php
/**
 * Cellform Video Dailymotion Service Class
 *
 *
 * @author rootofgeno@gmail.com
 */

class Cellform_Services_Video_Dailymotion extends Cellform_Services_VideoModel
{
	/**
	* Constructor, Initialize DOM
	*
	* @access public
	* @param string $url
	* @return
	*/
	public function __construct($url)
	{
		$this->Og_Load($url);
	}

	/**
	* Return video service name
	*
	* @access public
	* @return string
	*/
	public function GetVideoService()
	{
		return 'Dailymotion';
	}

	public function GetVideoMobile()
	{
		return $this->Og_VideoMobile();
	}

	/**
	* Return embeded video link
	*
	* @access public
	* @return string
	*/
	public function GetVideo()
	{
		return $this->Og_Video();
	}

	/**
	* Return image video presentation link
	*
	* @access public
	* @return string
	*/
	public function GetThumbnail()
	{
		return $this->Og_Thumbnail();
	}
}