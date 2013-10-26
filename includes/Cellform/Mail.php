<?php
/**
 * CellForm Mailing Class
 * Mail management
 *
 * @author rootofgeno@gmail.com
 */

class Cellform_Mail
{
	/**
	* Send an e-mail from EMAIL constant
	*
	* @access public
	* @return bool
	*/
	public static function Send($title, $body, $to)
	{
		if (isdefined('EMAIL'))
		{
			$headers  = 'From:' . trim(EMAIL) . "\n";
			$headers .= "Reply-To: " . trim(EMAIL) . "\n";
			$headers .= "Content-Type: text/html; charset=\"utf-8\"";

			if (!mail($to, $title, $body, $headers))
			{
				throw new Exception('Error function mail !');
			}

			return true;
		}
		
		return false;
	}
}

?>