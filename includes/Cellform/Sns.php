<?php
/**
 * CellForm Social Network Service Base
 * Management class connectivity services OAuth (with main Sns)
 * Component Sns
 *
 * @author rootofgeno@gmail.com
 */

class Cellform_Sns
{
	protected $_config = array();

	public function __construct(array $config = array())
	{
		if (empty($config['providers']))
		{
			throw new Exception('No specified provider in config.');
		}

		$this->_config = $config;
	}

	public function Authenticate($service)
	{
		if (!is_array($this->_config['providers'][$service]))
		{
			throw new Exception('Unknown Provider ID, check your configuration file.');
		}

		$config = $this->_config['providers'][$service];

		if (!$config['enabled'])
		{
			throw new Exception('The provider ' . $service . ' is not enabled.');
		}

		return Cellform_Injector::Instanciate('Cellform_Sns_' . $service, array($service, $config));
	}
}

?>