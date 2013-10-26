<?php
/**
 * CellForm Mail Class
 * Component Mail
 *
 * @author rootofgeno@gmail.com
 * @param MailController
 */

class Mail_Defaults extends Mail_Controller
{
	public function __construct(Mail_Controller $controller)
	{
		$this->_translate	= $controller->_translate;
		$this->_template	= $controller->_template;
	}

	/**
	* Send an email with HTML template
	*
	* @access public
	* @param string $view
	* @param string $to
	* @return bool
	*/
	public function Send($view, $to, array $data = array())
	{
		$title = $this->_translate->GetTranslation($view . '_Title');
		$title = SITENAME . ' - ' . $title;

		$body  = $this->_template->Render('mail_' . $view, array_merge($this->_translate->GetAllTranslation(), $data));

		return Cellform_Mail::Send($title, $body, $to);
	}
}

?>