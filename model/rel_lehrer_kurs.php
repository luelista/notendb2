<?php
  /**
   * Data Model for "rel_lehrer_kurs" table
   * 
   * @package    NotenDB2
   * @subpackage Models
   * @author     Moritz Willig <>
   * @see        DatabaseRelModel
   **/
	
	
	require_model("databaseRel");
  class Rel_lehrer_kursModel extends DatabaseRelModel {
    
    var $table = "rel_lehrer_kurs";
    var $idcol = "rid";
	var $idLCol="r_lid";
	var $idRCol="r_kuid";
	
    var $structure = array(
			"r_kuid" => "'%d'",
			"r_lid" => "'%d'"
		);
		
	function addByRel($lid,$kuid) {
		$this->sql("INSERT IGNORE INTO {$this->table} (".implode(",",array_keys($this->structure)).") VALUES (%d,%d)",$kuid,$lid);
		return $this->execute();
	}
	
	function deleteByRel($lid,$kuid) {
		$this->sql("DELETE FROM {$this->table} WHERE {$this->idLCol} = '%d' AND {$this->idRCol} = '%d'",$lid, $kuid);
		return $this->execute();
	}
	
	function deleteAllByKuid($kuid) {
		$this->sql("DELETE FROM {$this->table} WHERE {$this->idRCol} = '%d'", $kuid);
		return $this->execute();
	}
	
	function setById($lid,$kuid,$data) {
		$this->sql("UPDATE {$this->table} SET ".implode(",",$data)." WHERE {$this->idLCol} = '%d' AND {$this->idRCol} = '%d'"
			,$lid, $kuid);
		return $this->execute();
	}
	
  }
  
?>