<?php
  /**
   * Common Database Model
   * derived from TeamWiki Database Class
   * 
   * @package    MVC_Framework
   * @subpackage System
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
   **/
	
  class DatabaseModel {
    
		// Note: All Database models inheriting from "DatabaseModel"
		//       share ONE MySQL database connection, which is saved
		//       in $link
    static $link;
    static $is_connected = false;
    
		// Private, because they contain sensitive data
    private static $inifile;
    private static $settings;
    
		// For sql() function
    var $query;
		
		// Saves results from doQuery() / execute()
    var $lastresult;
	  
		// To be overridden in specific database models
    var $table = null;
    var $idcol = null;
	  var $structure=null;
	  
    function read_ini() {
			if (!$GLOBALS["DISABLE_TRACE"]) trace("Trying to read configuration from '{CONFIG_FILE}' ...");
      DatabaseModel::$inifile = CONFIG_FILE;
      DatabaseModel::$settings = parse_ini_file(DatabaseModel::$inifile,true);
      DatabaseModel::$settings["Salt"]= DatabaseModel::$settings["Login"]["Salt"];
			//if (!$GLOBALS["DISABLE_TRACE"]) trace("success!", DatabaseModel::$settings);
      return DatabaseModel::$settings;
    }
    
    function set_config($host, $user, $pass, $db) {
      DatabaseModel::$settings = array("Host"=>$host,"User"=>$user,"Pass"=>$pass,"Database"=>$db);
    }
    
		/**
		 * establish database connection if not $is_connected
		 **/
    function __construct($alt_db="") {
      if (DatabaseModel::$is_connected == true) return;
      if (!DatabaseModel::$settings) $this->read_ini();
      $sect = $alt_db==null?"DB":"alt_db_$alt_db";
      DatabaseModel::$link = @mysql_connect(DatabaseModel::$settings[$sect]['Host'], DatabaseModel::$settings[$sect]['User'], DatabaseModel::$settings[$sect]['Pass']);
      if (!DatabaseModel::$link) { trace("Could not connect to sql server. ".mysql_error()); trigger_error("Database Error occured. Please try again later.",E_USER_ERROR); }
      
      if (!@mysql_select_db(DatabaseModel::$settings[$sect]['Database'])) { trace("Could not select database. ".mysql_error()); trigger_error("Database Error occured. Please try again later.",E_USER_ERROR); }
      
      mysql_set_charset('utf8');
      mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
      
      DatabaseModel::$is_connected = true;
    }
    
    function disconnect() {
      mysql_close();
      $this->is_connected = false;
    }
    
    /**
		 * compose SQL queries injection-safe
		 **/
    function sql() {
      $this->sql_int(func_get_args());
    }
    
    /**
		 * @internal
		 **/
    function sql_int($params) {
      $max = count($params);
      if ($max < 2) {
        if ($params[0]{0} == "@") { trace(__CLASS__."::".__FUNCTION__, "Using named query: ".$q); $params[0] = $this->named_queries[substr($params[0],1)]; }
        $this->query = $params[0];
      } else {
        $q = $params[0];
        #if ($q{0} == "@") { trace(__CLASS__."::".__FUNCTION__, "Using named query: ".$q); $q = $this->named_queries[substr($q,1)]; }
        for($i=1; $i<$max; $i++) $args[] = mysql_real_escape_string($params[$i], DatabaseModel::$link);
        $this->query = vsprintf($q, $args);
      }
    }
    
		/**
		 *executes last SQL query
		 **/
    function execute() { $this->doQuery();
    }
		/**
		 *executes last SQL query
		 **/
    function doQuery($sql = null) {
      if($sql) $this->query = $sql;
      trace(__CLASS__."::".__FUNCTION__, $this->query);
      
      $this->lastresult = mysql_query($this->query);
      if (!$this->lastresult) { $this->dieWithError(); return false; }
      
      return $this->lastresult;
    }
    
    function nextRow() {
      $row = @mysql_fetch_assoc($this->lastresult);
      if (!$row) @mysql_free_result($this->lastresult);
      return $row;
    }
    
    function numRows() {
      return mysql_num_rows($this->lastresult);
    }
    
    function affectedRows() {
      return mysql_affected_rows();
    }
    
		/**
		 * @return int  the primary id of the last inserted row
		 **/
    function insertId() {
      return mysql_insert_id();
    }
    
    function dieWithError() {
      $trace = debug_backtrace();
      if(!$GLOBALS["DISABLE_TRACE"])trace(__CLASS__."::".__FUNCTION__, "SQL-ERROR", mysql_error()); trigger_error("Database Error occured. Please try again later. <b>".mysql_error()."</b> [".$trace[2]['file'].":".$trace[2]['line']."]",E_USER_ERROR); return false;
      
    }
    
    function printError() {
      printf("Es ist ein Datenbankfehler aufgetreten!<br>[%s] %s", mysql_errno(), mysql_error());
    }
    
    
    function get() {    //ehemals mysql_getone
      if (func_num_args()>0) {
        $q = func_get_arg(0);
        $max = func_num_args();
        for($i=1; $i<$max; $i++) $args[] = mysql_real_escape_string(func_get_arg($i), DatabaseModel::$link);
        $this->query = vsprintf($q, $args);
      }
      if (!$GLOBALS["DISABLE_TRACE"]) trace(__CLASS__."::".__FUNCTION__, $this->query);
      
      $result = mysql_query($this->query);
      if (!$result) { $this->dieWithError(); return false; }
      
      $row = @mysql_fetch_assoc($result);
      @mysql_free_result($result);
      
			return $row;
    }
    
    function getsingle() {
      if (func_num_args()>0) {
        $q = func_get_arg(0);
        $max = func_num_args();
        for($i=1; $i<$max; $i++) $args[] = mysql_real_escape_string(func_get_arg($i), DatabaseModel::$link);
        $this->query = vsprintf($q, $args);
      }
      if (!$GLOBALS["DISABLE_TRACE"]) trace(__CLASS__."::".__FUNCTION__, $this->query);
      
      $result = mysql_query($this->query);
      if (!$result) { $this->dieWithError(); return false; }
      
      $row = @mysql_fetch_row($result);
      @mysql_free_result($result);
      
      if (!$GLOBALS["DISABLE_TRACE"]) trace("getsingle???", $result, $row);
			
      return $row[0]; // return only first value!
    }
    
    function getlist() {   // ehemals mysql_getall
      if (func_num_args()>0) {
        $q = func_get_arg(0);
        $max = func_num_args();
        for($i=1; $i<$max; $i++) $args[] = mysql_real_escape_string(func_get_arg($i), DatabaseModel::$link);
        $this->query = vsprintf($q, $args);
      }
      if (!$GLOBALS["DISABLE_TRACE"]) trace(__CLASS__."::".__FUNCTION__, $this->query);
      
      $result = mysql_query($this->query);
      if (!$result) { $this->dieWithError(); return false; }
      
      $items = array();
      while ($row = @mysql_fetch_assoc($result)) {
        $items[] = $row;
      }
      @mysql_free_result($result);
      
      return $items;
    }
    
    function setdata($table, $idcol, $id, $structure, $data) {
      $cols = array();
      trace("setdata-structure", $structure);
      foreach($data as $k=>$v)
        if (isset($structure[$k]))
          $cols[$k] = sprintf($structure[$k], $v);
        else
          throw new Exception("Key '$k' not defined in structure.");
      
      trace("setdata-cols", $cols);
      if ($id == null)
        $this->sql("INSERT INTO {$table} (".implode(",",array_keys($cols)).")
            VALUES (".implode(",",array_values($cols)).")");
      else {
        $mydata = array();
        foreach($cols as $k=>$v) $mydata[] = "$k=$v";
        $this->sql("UPDATE {$table} SET ".implode(",",$mydata)." WHERE {$idcol} = %d", $id);
      }
      
      return $this->execute();
    }
    
		/**
		 * generate a password hash of the specified string
		 * @param string $STR       the string
		 * @param int    $rounds    how often the hash algorithm should be applied (the higher, the safer ;)
		 * @param string $username  username to be used as salt
		 **/
		function hash($STR, $rounds, $username) {
			###trace("DataToHash", DatabaseModel::$settings["Salt"].$username."@", $STR);
			for($i = 0; $i < $rounds; $i++) {
				$STR = md5(DatabaseModel::$settings["Salt"].$username."@".$STR);
			}
			return $STR;
	  }
		
    function escape($str) {
      return mysql_real_escape_string($str, DatabaseModel::$link);
    }
    
    function set($id, $data) {
      $this->setdata($this->table, $this->idcol, $id, $this->structure, $data);
    }
      
    function delete($id) {
      $this->sql("DELETE FROM {$this->table} WHERE {$this->idcol} = %d", $id);
      return $this->execute();
    }

    function get_all($order_by = "") {
      $this->sql("SELECT * FROM {$this->table}".($order_by?" ORDER BY $order_by":""));
      return $this->getlist();
    }
      
    function get_by_id($id) {
      $this->sql("SELECT * FROM {$this->table} WHERE {$this->idcol} = %d", $id);
      return $this->get();
    }
	
    
  }
  
?>