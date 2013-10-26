<?php
/**
 * CellForm Mysql Database Class
 * Only for mysql >= 4.1.0 (Support UTF-8)
 * Must be aliased in Cellform_DB
 *
 * @author rootofgeno@gmail.com
 */

class Cellform_DB_Mysql extends Cellform_Dms
{
	/**
	* Connect to database.
	*
	* @access public
	* @param string $host
	* @param string $user
	* @param string $password
	* @param string $database
	* @return resource|array
	*/
	public function SqlConnect($host, $user, $password, $database, $persistency = false)
	{
		$this->_persistency = $persistency;
		$this->_user		= $user;
		$this->_server		= $host;
		$this->_dbname		= $database;

		$this->_connid = ($this->_persistency) ? @mysql_pconnect($this->_server, $this->_user, $password) : @mysql_connect($this->_server, $this->_user, $password);

		if ($this->_connid && $this->_dbname != '')
		{
			if (@mysql_select_db($this->_dbname, $this->_connid))
			{
				@mysql_query("SET NAMES 'utf8'", $this->_connid);
				return $this->_connid;
			}
		}

		return $this->SqlError();
	}

	/**
	* Send sql query & returns data.
	*
	* @access public
	* @param string $query
	* @return resource|array|false
	*/
	public function SqlQuery($query, $ttl = '', $cachename = '')
	{
		$this->_query = $query;
		$this->_ttl   = $ttl;
		$this->_cachename  = $cachename;

		if ($this->_ttl == '')
		{
			return $data = $this->_SqlFetch();
		}
		else
		{
			if (!$data = $this->SqlCached())
			{
				return false;
			}

			return $data;
		}
	}

	/**
	* Return resource _query_res.
	*
	* @access public
	* @return bool
	*/
	public function SqlQueryRes()
	{
		return ($this->_query_res) ? $this->_query_res : false;
	}

	/**
	* Count the number of rows.
	*
	* @access public
	* @return int|false
	*/
	public function SqlNumRows()
	{
		return ($this->_query_res) ? intval(@mysql_num_rows($this->_query_res)) : false;
	}

	/**
	* Count the number of affected rows by INSERT, UPDATE, REPLACE or DELETE.
	*
	* @access public
	* @return int|false
	*/
	public function SqlAffectedRows()
	{
		return ($this->_connid) ? intval(@mysql_affected_rows($this->_connid)) : false;
	}

	/**
	* Get the ID generated for an AUTO_INCREMENT column by the previous query (usually INSERT).
	*
	* @access public
	* @return int|false
	*/
	public function SqlLastId()
	{
		return ($this->_connid) ? intval(@mysql_insert_id($this->_connid)) : false;
	}

	/**
	* Return a sanitized string for SqlQuery.
	*
	* @access public
	* @param string $value
	* @return string|false
	*/
	public function SqlEscape($string)
	{
		if (!$this->_connid)
		{
			return @mysql_real_escape_string($string);
		}

		return @mysql_real_escape_string($string, $this->_connid);
	}

	/**
	* Return an array that corresponds to the fetch row.
	* It is a mandatory method
	*
	* @access protected
	* @return array|bool
	*/
	protected function _SqlFetch()
	{
		if (($this->_query_res = @mysql_query($this->_query, $this->_connid)) === false)
		{
			$this->SqlError($this->_query);
		}

		while ($row = @mysql_fetch_array($this->_query_res, MYSQL_ASSOC))
		{
			$data[] = $row;
		}

		return (empty($data)) ? false : $data;
	}

	/**
	* Return an array of errors (mysql_error() & mysql_errno()).
	* It is a mandatory method
	*
	* @access protected
	* @return array
	*/
	protected function _SqlError()
	{
		if (!$this->_connid)
		{
			return array(
				'message'	=> @mysql_error(),
				'code'		=> @mysql_errno(),
			);
		}

		return array(
			'message'	=> @mysql_error($this->_connid),
			'code'		=> @mysql_errno($this->_connid),
		);
	}
}

class_alias ('Cellform_DB_Mysql', 'Cellform_DB');

?>