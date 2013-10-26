<?php
/**
 * CellForm Injector Class
 *
 * @author rootofgeno@gmail.com
 */

class Cellform_Injector
{
	/**
	* Creating New Class Instances
	*
	* @access public
	* @param string $class
	* @return object
	*/
    public static function Instanciate($class, array $args = array())
	{
        if (!class_exists($class, true))
		{
			throw new Exception('Class does not exist : ' . $class);
		}

        $reflect = new ReflectionClass($class);

        if (!$constructor = $reflect->getConstructor())
		{
            return new $class;
		}

        return !empty($args) ? $reflect->newInstanceArgs($args) : $reflect->newInstance();
    }
}

?>