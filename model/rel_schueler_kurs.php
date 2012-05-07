<?php
  /**
   * Data Model for "rel_schueler_kurs" table
   * 
   * @package    NotenDB2
   * @subpackage Models
   * @author     Moritz Willig <>
   * @see        DatabaseRelModel
   **/
	
	require_model("databaseRel");
  class Rel_schueler_kursModel extends DatabaseRelModel {
    
    var $table = "rel_schueler_kurs";
    var $idcol = "rid";
	var $idLCol="r_sid";
	var $idRCol="r_kuid";
	
    var $structure = array(
			"r_sid" => "'%d'",
			"r_kuid" => "'%d'",
			"note" => "'%s'",
			"fehlstunden" => "%d",
			"fehlstunden_un" => "%d",
			"kommentar" => "'%s'",
		);
  
	function addByRelEmpty($sid,$kuid) {
		$this->sql("INSERT IGNORE INTO {$this->table} (r_sid,r_kuid) VALUES (%d,%d)",$sid,$kuid);
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
	
	
  }
  
?>