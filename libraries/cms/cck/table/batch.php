<?php
/**
* @version 			SEBLOD 3.x Core ~ $Id: batch.php sebastienheraud $
* @package			SEBLOD (App Builder & CCK) // SEBLOD nano (Form Builder)
* @url				http://www.seblod.com
* @editor			Octopoos - www.octopoos.com
* @copyright		Copyright (C) 2013 SEBLOD. All Rights Reserved.
* @license 			GNU General Public License version 2 or later; see _LICENSE.php
**/

defined( '_JEXEC' ) or die;

// JCckTableBatch
class JCckTableBatch extends JObject
{
	protected $_tbl				=	'';
	protected $_tbl_rows		=	array();
	protected $_tbl_rows_sql	=	'';
	protected $_db;
	
	// __construct
	function __construct( &$db, $table )
	{
		$this->_tbl	=	$table;
		$this->_db	=	&$db;
		
		if ( $fields = $this->getFields() ) {
			foreach ( $fields as $name => $v ) {
				if ( !property_exists( $this, $name ) ) {
					$this->$name	=	null;
				}
			}
		}
	}
	
	// getInstance
	public static function getInstance( $table )
	{
		$db			=	JFactory::getDbo();
		$tableClass	=	'JCckTableBatch';
		
		// Instantiate
		$instance	=	new $tableClass( $db, $table );
		
		return $instance;
	}
	
	// getFields
	public function getFields()
	{
		$name			=	$this->_tbl;
		static $cache	=	array();
		
		if ( ! isset( $cache[$name] ) ) {
			$fields	=	$this->_db->getTableColumns( $name, false );
			if ( empty( $fields ) ) {
				$e	=	new JException( JText::_( 'JLIB_DATABASE_ERROR_COLUMNS_NOT_FOUND' ) );
				$this->setError( $e );
				return false;
			}
			$cache[$name]	=	$fields;
		}
		
		return $cache[$name];
	}
	
	// bind
	public function bind( $rows )
	{
		if ( count( $rows ) ) {
			$this->_tbl_rows	=	$rows;
		}
	}
	
	// check
	public function check( $force = array(), $ignore = array(), $init = array() )
	{
		$str	=	'';
		
		if ( count( $this->_tbl_rows ) ) {
			foreach ( $this->_tbl_rows as $row ) {
				$str2	=	'';
				if ( count( $force ) ) {
					foreach ( $force as $k=>$v ) {
						$row->$k	=	$v;
					}
				}
				if ( count( $init ) ) {
					foreach ( $init as $k=>$v ) {
						if ( !isset( $row->$k ) ) {
							$row->$k	=	$v;
						}
					}
				}
				foreach ( $this->getProperties() as $k=>$v ) {
					if ( !in_array($k, $ignore ) ) {
						if ( isset( $row->$k ) ) {
							$str2	.=	'"'.$this->_db->escape( $row->$k ).'", ';
						}
					}
				}
				if ( $str2 != '' ) {
					$str2	=	substr( trim( $str2 ), 0, -1 );
					$str	.=	'(' . $str2 . '), ';
				}
			}
		}
		
		if ( $str != '' ) {
			$str	=	substr( trim( $str ), 0, -1 );
		}
		
		$this->_tbl_rows_sql	=	$str;
	}
	
	// delete
	public function delete( $where_clause )
	{
		$where	=	'';
		if ( is_array( $where_clause ) ) {
			if ( count( $where_clause ) ) {
				foreach ( $where_clause as $k=>$v ) {
					$where	.=	' AND '.$k . ' = "'.$v.'"';
				}
				$where	=	substr( $where, 5 );
			}
		} else {
			$where	=	$where_clause;
		}
		
		if ( !$where ) {
			return false;
		}
		
		$query	=	'DELETE FROM '.$this->_tbl.' WHERE '.$where;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->execute() ) {
			return false;
		}
	}
	
	// load
	public function load( $where_clause, $alter = array() )
	{
		$str	=	'';
		$where	=	'';
		if ( is_array( $where_clause ) ) {
			if ( count( $where_clause ) ) {
				foreach ( $where_clause as $k=>$v ) {
					$where	.=	' AND '.$k . ' = "'.$v.'"';
				}
				$where	=	substr( $where, 5 );
			}
		} else {
			$where	=	$where_clause;
		}
		
		$query	=	'SELECT * FROM '.$this->_tbl.' WHERE '.$where;
		$this->_tbl_rows	=	JCckDatabase::loadObjectList( $query );
	}
	
	// save
	public function save( $rows = array(), $force = array(), $ignore = array(), $init = array() )
	{
		$this->bind( $rows );
		$this->check( $force, $ignore, $init );
		$this->store();
	}
	
	// store
	public function store()
	{
		if ( !$this->_tbl_rows_sql ) {
			return false;
		}
		
		$query	=	'INSERT IGNORE INTO '.$this->_tbl.' VALUES '.$this->_tbl_rows_sql;
		$this->_db->setQuery( $query );
		if ( ! $this->_db->execute() ) {
			return false;
		}
	}
}
?>