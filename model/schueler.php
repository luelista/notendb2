<?php
  /**
   * Data Model for "Schueler" table
   * 
   * @package    NotenDB2
   * @subpackage Models
   * @author     Moritz Willig <>
   **/
	
  class SchuelerModel extends DatabaseModel {
    
    var $DID;
    
    var $table = "schueler";
    var $idcol = "sid";
    var $structure = array(
             "did" => "'%s'",
             "name" => "'%s'",
             "vorname" => "'%s'",
             "geburtsdatum" => "'%s'",
			 "username" => "'%s'",
			 "klasse" => "'%s'",
             "strasse" => "'%s'",
             "plz" => "'%s'",
             "ort" => "'%s'",
             "telefon" => "'%s'",
             "bemerkungen" => "'%s'",
             "kommentar" => "'%s'",
             "ist_g8" => "'%s'",
             "fachrichtung" => "'%s'"
        );
    
    
    function get_all_by_kurs($kuid) {
      $this->sql("SELECT s.* FROM schueler AS s INNER JOIN rel_schueler_kurs AS rsk ON s.sid=rsk.r_sid WHERE s.did = %d AND rsk.r_kuid = %d ORDER BY name,vorname", $this->DID, $kuid);
      return $this->getlist();
    }
    
    function set($id, $data) {
      $data["did"] = $this->DID;
      $this->setdata($this->table, $this->idcol, $id, $this->structure, $data);
    }
      
    function delete($id) {
      $this->sql("DELETE FROM {$this->table} WHERE {$this->idcol} = %d AND did = %d", $id, $this->DID);
      return $this->execute();
    }

    function get_all() {
      $this->sql("SELECT * FROM {$this->table} WHERE did = %d ORDER BY name, vorname", $this->DID);
      return $this->getlist();
    }
      
    function get_by_id($id) {
      $this->sql("SELECT * FROM {$this->table} WHERE {$this->idcol} = %d AND did = %d", $id, $this->DID);
      return $this->get();
    }
	
    
    
  }
  
?>