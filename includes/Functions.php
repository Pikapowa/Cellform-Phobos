<?php
/**
 * CellForm Functions
 * Procedural source code
 *
 * @author rootofgeno@gmail.com
 */

function isdefined($def)
{
	return defined($def) ? constant($def) : false;
}

function random($length)
{
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyz123456789'), 0, $length);
}

function remove_remarks($source)
{
	$output = '';
	$lines  = explode("\n", $source);

	foreach($lines as $numline => $line)
	{
		$line = trim($line);

		if (!empty($line) && $line[0] != '#')
		{
			$output .= $line . "\n";
		}
	}

	return $output;
}

function unlink_evolved($filepath)
{
	if (file_exists($filepath))
	{
		if (unlink($filepath))
		{
			return true;
		}
		else
		{
			throw new Exception('I can\'t delete file : ' . $filepath);
		}
	}
	else
	{
		throw new Exception('File does not exist : ' . $filepath);
	}
}

?>