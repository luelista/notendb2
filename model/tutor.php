<?php
  /**
   * Data Model for "tutor" table
   * 
   * @package    NotenDB2
   * @subpackage Models
   * @author     Max Weller <max.weller@teamwiki.net>
   * @see        DatabaseRelModel
   **/
	
	require_model("databaseRel");
  class TutorModel extends DatabaseRelModel {
  
  var $table = "tutor";
  var $idcol = "tid";
	var $idLCol="r_did";
	var $idRCol="r_lid";
	
  var $structure = array(
    "r_did" => "'%d'",
    "r_lid" => "'%d'"
  );
  
	function addByRel($did,$lid) {
		$this->sql("INSERT IGNORE INTO {$this->table} (".implode(",",array_keys($this->structure)).") VALUES (%d,%d)",$did,$lid);
		return $this->execute();
	}
	
	function deleteByRel($did,$lid) {
		$this->sql("DELETE FROM {$this->table} WHERE {$this->idLCol} = '%d' AND {$this->idRCol} = '%d'",$did, $lid);
		return $this->execute();
	}
	
	function deleteAllByDid($did) {
		$this->sql("DELETE FROM {$this->table} WHERE {$this->idLCol} = '%d'", $did);
		return $this->execute();
	}
	
	function setById($did,$lid,$data) {
		$this->sql("UPDATE {$this->table} SET ".implode(",",$data)." WHERE {$this->idLCol} = '%d' AND {$this->idRCol} = '%d'"
			,$did, $lid);
		return $this->execute();
	}
	
  }
  
?>