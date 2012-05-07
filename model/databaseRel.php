<?php
  /**
   * Abstract Database Model for foreign key tables
   * 
   * @package    NotenDB2
   * @subpackage Models
   * @author     Moritz Willig <>
   **/
	
  class DatabaseRelModel extends DatabaseModel {
    
	var $idLCol=null;
	var $idRCol=null;
	
	/**
	 * Move to Database?
	**/
	function addByRel($data) {
		$this->sql("INSERT INTO {$this->table}(".implode(",",array_keys($structure)).") VALUES ('%d')",$data);
		return $this->execute();
	}
	
	function deleteByRel($lId,$rId) {
		$this->sql("DELETE * FROM {$this->table} WHERE {$this->idLCol} = '%d' AND {$this->idRCol} = '%d'",$lId, $rId);
		return $this->execute();
	}
	
	function setByRel($lId,$rId,$data) {	
		$cols = array();
      foreach($data as $k=>$v)
        if (isset($this->structure[$k]))
          $cols[$k] = sprintf($this->structure[$k], $v);
        else
          throw new Exception("Key '$k' not defined in structure.");
      
      $mydata = array();
      foreach($cols as $k=>$v) $mydata[] = "$k=$v";
#     /* echo "<hr>setByRel: {$this->idLCol}=$lId, {$this->idRCol}=$rId, ";	
      //echo implode(",",$mydata)."<hr>";
      $this->sql("UPDATE {$this->table} SET ".implode(",",$mydata)." WHERE {$this->idLCol} = '%d' AND {$this->idRCol} = '%d'"
			,$lId, $rId);
		
		return $this->execute();
	}
	
	function getByRel($lId,$rId) {
		$this->sql("SELECT * FROM {$this->table} WHERE {$this->idLCol}='%d' AND {$this->idRCol}='%d'",$lId,$rId);
		return $this->get();
	}
	
	function getAllByLId($lid) {
		$this->sql("SELECT * FROM {$this->table} WHERE {$this->idLCol}='%d'",$lId);
		return $this->getlist();
	}
	
	function getAllByRId($rId) {
		$this->sql("SELECT * FROM {$this->table} WHERE {$this->idRCol}='%d'",$rId);
		return $this->getlist();
	}
	
  }
  
?>