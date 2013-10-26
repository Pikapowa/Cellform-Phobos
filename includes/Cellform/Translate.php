<?php
/**
 * CellForm Translation Class
 * Management file translation (XML)
 * 
 * @author rootofgeno@gmail.com
 */

class Cellform_Translate
{
	protected $_translations = array();
	protected $_xmlfile;
	protected $_language;

	public function __construct($xmlfile = ACTIVE_MODULE, $language = ACTIVE_LANG)
	{
		$this->LoadLanguage($xmlfile, $language);
		$this->_MergeConstant();
	}

	/**
	* Return the value corresponds to array translation key
	*
	* @access public
	* @param string $key
	* @return string|false
	*/
	public function GetTranslation($key, $args = '')
	{
		if (!empty($key) && isset($this->_translations[$key]))
		{
			if (!empty($args))
			{
				return vsprintf($this->_translations[$key], $args);
			}
			else
			{
				return $this->_translations[$key];
			}
		}

		return false;
	}

	/**
	* Set value(s) in translation buffer
	*
	* @access public
	* @param string $value
	* @param mixed $data
	*/
	public function SetTranslation($key, $value)
	{
		$this->_translations[$key] = $value;
	}

	/**
	* Merge array in translation buffer
	*
	* @access public
	* @param array $translations
	*/
	public function MergeTranslation(array $translations = array())
	{
		$this->_translations = array_merge($this->_translations, $translations);
	}

	/**
	* Return translation array
	*
	* @access public
	* @return array|false
	*/
	public function GetAllTranslation()
	{
		return !empty($this->_translations) ? $this->_translations : false;
	}

	/**
	* Return number of elements
	*
	* @access public
	* @return int
	*/
	public function GetNumberLine()
	{
		return count($this->_translations);
	}

	/**
	* Load an particular translation file
	*
	* @access public
	* @param string $xmlfile
	* @param string $language
	*/
	public function LoadLanguage($xmlfile, $language)
	{
		$this->_xmlfile = $xmlfile;
		$this->_language = $language;

		if (!file_exists(LANGUAGE_PATH . $this->_language))
		{
			$this->_language = DEFAULTS_LANG;
		}

		if (!file_exists(LANGUAGE_PATH . $this->_language . DIRECTORY_SEPARATOR . $this->_xmlfile . '.xml'))
		{
			$this->_xmlfile = DEFAULTS_MODULE;
		}

		$this->_translations = $this->_LoadLanguageFromXML();
    }

    /**
    * Return language mode
    *
    * @access public
    * @return string
    */
    public function GetLanguage()
    {
    	return $this->_language;
    }

	/**
	* Read & interpret translation file
	*
	* @access private
	* @param string $xmlfile
	* @param string $language
	* @return array
	*/
	private function _LoadLanguageFromXML()
	{
		$path = LANGUAGE_PATH . $this->_language . DIRECTORY_SEPARATOR . $this->_xmlfile . '.xml';

		if (!file_exists($path))
		{
			throw new Exception('The language selected (' . $path . ') is not a valid language file');
		}

		$xml = new SimpleXMLElement($path, null, true);
		$translations = array();

		foreach ($xml->children() as $label)
		{
			$index = (string)$label['index'];
			$translations[$index] = (string)$label;
		}

		return $translations;
	}

	/**
	* Merge constant with translation array (Independant of any language)
	*
	* @access private
	*/
	private function _MergeConstant()
	{
		$translation = array();

		$translation['GETHOST']			= $_SERVER['SERVER_NAME'];
		$translation['DEFAULTS_LANG']	= isdefined('DEFAULTS_LANG');
		$translation['ID']				= isdefined('ID');
		$translation['TITLE']			= isdefined('TITLE');
		$translation['DESC']  			= isdefined('DESCRIPTION');
		$translation['SITENAME']		= isdefined('SITENAME');
		$translation['EMAIL']			= isdefined('EMAIL');

		$translation['FACEBOOK_ENABLED'] = isdefined('FACEBOOK_ENABLED');
		$translation['FACEBOOK_APPID'] 	 = isdefined('FACEBOOK_APPID');
		$translation['FACEBOOK_SECRET']  = isdefined('FACEBOOK_SECRET');

		$translation['GOOGLE_ENABLED']	= isdefined('GOOGLE_ENABLED');
		$translation['GOOGLE_APPID'] 	= isdefined('GOOGLE_APPID');
		$translation['GOOGLE_SECRET'] 	= isdefined('GOOGLE_SECRET');

		$this->_translations = array_merge($this->_translations, $translation);
	}
}

?>
