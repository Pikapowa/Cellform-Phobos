<?php
/**
 * CellForm Database Manager Class
 * Abstraction layer for the database management
 *
 * @author rootofgeno@gmail.com
 */

abstract class Cellform_Dms
{
	protected $_persistency;
	protected $_user;
	protected $_server;
	protected $_dbname;
	protected $_connid;
	protected $_query;
	protected $_query_res;
	protected $_cachename;
	protected $_ttl;

	private $_errors = array();
	private $_data_ts;
	private $_filename;

	abstract public function SqlConnect($host, $user, $password, $database, $persistency);
	abstract public function SqlQuery($query, $ttl, $cachename);
	abstract public function SqlNumRows();
	abstract public function SqlAffectedRows();
	abstract public function SqlLastId();
	abstract public function SqlEscape($string);

	abstract protected function _SqlError();
	abstract protected function _SqlFetch();

	/**
	* Function errors management.
	*
	* @access public
	* @param string $query
	* @return bool
	*/
	public function SqlError($query = '')
	{
		$this->_errors = $this->_SqlError($this->_connid);
		$message = ($query) ? 'QUERY SQL ERROR' : 'SQL ERROR';
		$message .= ' [ ' . $this->_errors['message'] . ' - ' . $this->_errors['code'] . ']';

		throw new Exception($message);
	}

	/**
	* Set SQL caching.
	*
	* @access protected
	* @return array|false
	*/
	protected function SqlCached()
	{
		if (empty($this->_cachename))
		{
			$this->_cachename = md5($this->_query);
		}

		$this->_filename = ROOT . '/cache/_db/' . $this->_cachename;
		$this->_SqlGetfile_ts();

		if ((time() - $this->_data_ts) >= $this->_ttl)
		{
			$data = $this->_SqlFetch();

			if (!$this->_SqlSaveCache($data))
			{
				return false;
			}

			return $data;
		}
		else
		{
			return $data = $this->_SqlGetCache();
		}
	}

	/**
	* Write data in the cache.
	*
	* @access private
	* @param string $data
	* @return bool
	*/
	private function _SqlSaveCache($data)
	{
		if (!$fp = @fopen($this->_filename, 'w'))
		{
			return false;
		}

		if (!@fwrite($fp,serialize($data)))
		{
			fclose($fp);
			return false;
		}

		fclose($fp);

		return true;
	}

	/**
	* Read data in the cache.
	*
	* @access private
	* @return array|false
	*/
	private function _SqlGetCache()
	{
		if (!$x = @file_get_contents($this->_filename))
		{
			return false;
		}

		if (!$data = unserialize($x))
		{
			return false;
		}

		return $data;
	}

	/**
	* Get timestamp of cache.
	*
	* @access private
	* @return bool
	*/
	private function _SqlGetFile_Ts()
	{
		if (!file_exists($this->_filename))
		{
			$this->_data_ts = 0;
			return false;
		}

		$this->_data_ts = filemtime($this->_filename);
		return true;
	}
}

?>
