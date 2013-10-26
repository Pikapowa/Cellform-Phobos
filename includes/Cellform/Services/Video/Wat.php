<?php
/**
 * Cellform Video Wat Service Class
 *
 *
 * @author rootofgeno@gmail.com
 */

class Cellform_Services_Video_Wat extends Cellform_Services_VideoModel
{
	/**
	* Constructor, Initialize DOM
	*
	* @access public
	* @param $url
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
		return 'Wat';
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
		$url = $this->Og_VideoMobile();
		$url = explode('/', $url);

		$id = $url[count($url) - 1];
		$url = 'http://www.wat.tv/swf2/' . $id;

		return $url;
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

?>