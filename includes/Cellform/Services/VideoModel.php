<?php
/**
 * Cellform Video Class Base
 * (With OpenGraph)
 *
 *
 * @author rootofgeno@gmail.com
 */

abstract class Cellform_Services_VideoModel
{
	protected $_xp;

	abstract public function GetVideoService();
	abstract public function GetVideoMobile();
	abstract public function GetVideo();
	abstract public function GetThumbnail();

	/**
	* Load DOM path
	*
	* @access protected
	* @param $url
	* @return
	*/
	protected function Og_Load($url)
	{
		libxml_use_internal_errors(true);

		if (($html = @file_get_contents($url)))
		{
			$dom = new DomDocument();
			$dom->loadHTML($html);
			$this->_xp = new domxpath($dom);
		}
	}

	protected function Og_VideoMobile()
	{
		if ($this->_xp)
		{
			foreach($this->_xp->query("//meta[@name='twitter:player']") as $key)
			{
				if ($embed = $key->getAttribute('content'))
				{
					return $embed;
				}
				else
				{
					return $key->getAttribute('value');
				}
			}
		}
	}
	/**
	* Parse DOM for retrieve meta data attribute (twitter:player)
	* Return embeded video link
	*
	* @access protected
	* @return string
	*/
	protected function Og_Video()
	{
		if ($this->_xp)
		{
			foreach($this->_xp->query("//meta[@property='og:video']") as $key)
			{
				return $key->getAttribute('content');
			}
		}
	}

	/**
	* Parse DOM for retrieve meta data attribute (og:image)
	* Return thumbnail
	*
	* @access protected
	* @return string
	*/
	protected function Og_Thumbnail()
	{
		if ($this->_xp)
		{
			foreach($this->_xp->query("//meta[@property='og:image']") as $key)
			{
				return $key->getAttribute('content');
			}
		}
	}
}

?>