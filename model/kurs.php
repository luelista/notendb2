<?php
  /**
   * Data Model for "Kurs" table
   * 
   * @package    NotenDB2
   * @subpackage Models
   * @author     Max Weller <max.weller@wikilab.de>, Moritz Willig <>
   **/
	
  class KursModel extends DatabaseModel {
    
    var $DID;
    var $table = "kurs";
    var $idcol = "kuid";
    var $structure = array(
			"did" => "%d",
			"name" => "'%s'",
			"gewichtung" => "'%d'",
			"art" => "'%s'",
			"wochenstunden" => "'%s'",
			"thema" => "'%s'",
			"display_position" => "%d",
			"export_position" => "%d",
      "editlocked_by_lid" => "%d",
      "editlocked_since" => "'%s'"
		);
		
    
    function get_all_with_lehrer_namen() {
      $this->sql("SELECT k.kuid,k.name,k.art,k.wochenstunden,GROUP_CONCAT(l.name) AS lehrer_namen FROM kurs AS k LEFT OUTER JOIN rel_lehrer_kurs AS rlk ON k.kuid=rlk.r_kuid LEFT OUTER JOIN lehrer AS l ON rlk.r_lid=l.lid WHERE did = %d GROUP BY k.kuid ORDER BY k.display_position,k.name,l.name", $this->DID);
      return $this->getlist();
    }
    function get_all_with_lehrer_namen_and_permission($lid) {
      $this->sql("SELECT
                 k.kuid,k.name,k.art,k.wochenstunden,GROUP_CONCAT(l.name) AS lehrer_namen,
                 FIND_IN_SET('%d',GROUP_CONCAT(l.lid)) AS lehrer_perm FROM kurs AS k
                 LEFT OUTER JOIN rel_lehrer_kurs AS rlk ON k.kuid=rlk.r_kuid
                 LEFT OUTER JOIN lehrer AS l ON rlk.r_lid=l.lid
                 WHERE did = %d
                 GROUP BY k.kuid
                 ORDER BY k.display_position,k.name,l.name",
                 $lid, $this->DID);
      
      return $this->getlist();
      
    }
    function get_all_with_lehrer_namen_by_export_position() {
      $this->sql("SELECT k.kuid,k.name,k.art,k.wochenstunden,k.export_position,k.thema,GROUP_CONCAT(l.name) AS lehrer_namen FROM kurs AS k LEFT OUTER JOIN rel_lehrer_kurs AS rlk ON k.kuid=rlk.r_kuid LEFT OUTER JOIN lehrer AS l ON rlk.r_lid=l.lid WHERE did = %d GROUP BY k.kuid ORDER BY k.export_position, k.display_position", $this->DID);
      return $this->getlist();
    }
    function get_by_lid_with_lehrer_namen($lid) {
      $this->sql("SELECT k.kuid,k.name,k.art,k.wochenstunden,GROUP_CONCAT(l.name) AS lehrer_namen FROM kurs AS k INNER JOIN rel_lehrer_kurs AS rlk2 ON k.kuid=rlk2.r_kuid LEFT OUTER JOIN rel_lehrer_kurs AS rlk ON k.kuid=rlk.r_kuid LEFT OUTER JOIN lehrer AS l ON rlk.r_lid=l.lid WHERE did = %d AND rlk2.r_lid=%d GROUP BY k.kuid ORDER BY k.name,l.name", $this->DID, $lid);
      return $this->getlist();
    }
    function get_by_kuid_with_lehrer_namen($kuid) {
      $this->sql("SELECT k.kuid,k.name,k.art,k.wochenstunden,GROUP_CONCAT(l.name) AS lehrer_namen FROM kurs AS k LEFT OUTER JOIN rel_lehrer_kurs AS rlk ON k.kuid=rlk.r_kuid LEFT OUTER JOIN lehrer AS l ON rlk.r_lid=l.lid WHERE did = %d AND k.kuid=%d GROUP BY k.kuid ORDER BY k.name,l.name", $this->DID, $kuid);
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
      $this->sql("SELECT * FROM {$this->table} WHERE did = %d ORDER BY name", $this->DID);
      return $this->getlist();
    }
    
    function get_by_id($id) {
      $this->sql("SELECT * FROM {$this->table} WHERE {$this->idcol} = %d AND did = %d", $id, $this->DID);
      return $this->get();
    }
	
    
  }
  
?>