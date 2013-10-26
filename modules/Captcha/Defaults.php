<?php
/**
 * CellForm Captcha Class
 * Component Captcha
 *
 * @author rootofgeno@gmail.com
 * @param CaptchaController
 */

class Captcha_Defaults extends Captcha_Controller
{
	public function __construct(Captcha_Controller $controller)
	{
		$this->_http 		= $controller->_http;
		$this->_translate	= $controller->_translate;
	}

	/**
	* Return the correct answer
	*
	* @access public
	* @return strings
	*/
	public function ReturnAnswer()
	{
		return $this->_translate->GetTranslation('A' . $_SESSION['captcha']);
	}

	/**
	* Output the captcha in JSON format
	*
	* @access public
	*/
	public function CaptchaJson()
	{
		$captcha_json = $this->_ReturnCaptcha();
		echo $this->_http->GetPostBody('application/json', $captcha_json);
	}

	/**
	* Output the captcha in RAW format
	*
	* @access public
	* @return string
	*/
	public function CaptchaRaw()
	{
		return $this->_ReturnCaptcha();
	}

	/**
	* Active & return captcha question
	*
	* @access private
	* @param int $amplitude
	* @return string
	*/
	private function _ReturnCaptcha()
	{
		$nb_lines = $this->_translate->GetNumberLine();

		$captcha_id = mt_rand(1, 7); // 7 is the number of question/response

		$_SESSION['captcha'] = $captcha_id;

		return $this->_translate->GetTranslation('Q' . $captcha_id);
	}
}


?>