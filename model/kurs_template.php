<?php
  /**
   * Data Model for "Kurs_template" table
   * 
   * @package    NotenDB2
   * @subpackage Models
   * @author     Max Weller <max.weller@wikilab.de>, Moritz Willig <>
   **/
	
  class Kurs_templateModel extends DatabaseModel {
    
    var $DID;
    var $table = "kurs_template";
    var $idcol = "ktid";
    var $structure = array(
      "schulform" => "'%s'",
      "stufe" => "'%s'",
      "hj" => "'%s'",
			"name" => "'%s'",
			"gewichtung" => "'%d'",
			"art" => "'%s'",
			"wochenstunden" => "'%s'",
			"thema" => "'%s'",
			"display_position" => "%d",
			"export_position" => "%d",
      "fachrichtung" => "'%s'"
		);
		
    function set($id, $data) {
      $this->setdata($this->table, $this->idcol, $id, $this->structure, $data);
    }
    
    function delete($id) {
      $this->sql("DELETE FROM {$this->table} WHERE {$this->idcol} = %d ", $id, $this->DID);
      return $this->execute();
    }
    
    function get_all($orderby="display_position") {
      $this->sql("SELECT * FROM {$this->table} ORDER BY $orderby", $this->DID);
      return $this->getlist();
    }
    function get_all_by_target($schulform,$stufe,$hj) {
      $this->sql("SELECT * FROM {$this->table} WHERE schulform = '%s' AND stufe = '%s' AND hj = '%s' ORDER BY display_position", $schulform, $stufe, $hj);
      return $this->getlist();
    }
    
    function get_by_id($id) {
      $this->sql("SELECT * FROM {$this->table} WHERE {$this->idcol} = %d ", $id, $this->DID);
      return $this->get();
    }
    
  }
  
?>