<?php
/**
 * CellForm Class Validator Version 1.2
 * Différentes règles de validation des données utilisateurs
 *
 * @author rootofgeno@gmail.com
 * @return
 */

require_once(ROOT . '/lib/htmLawed.php');

class Cellform_Filter
{
	public function Filter_SanitizeString($value, $params = null)
	{
		return filter_var($value, FILTER_SANITIZE_STRING);
	}

	public function Filter_Safe($value, $params = null)
	{
		$config = array(
			'safe'	=> 1
		);

		return htmLawed($value, $config);
	}

	public function Filter_Htmlencode($value, $params = null)
	{
		return htmlentities($value);
	}
}

?>