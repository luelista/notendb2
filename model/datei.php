<?php
  /**
   * Data Model for "Datei" table
   * 
   * @package    NotenDB2
   * @subpackage Models
   * @author     Moritz Willig <>
   **/
	
  class DateiModel extends DatabaseModel {
    
    var $table = "datei";
    var $idcol = "did";
    var $structure = array(
          "jahr", "hj", "schulform", "stufe", "archiviert"
      );
    
    function k_set_editlock($kuid, $lid) {
      $this->sql("UPDATE kurs SET editlocked_by_lid = %d, editlocked_since = NOW() WHERE kuid = %d AND editlocked_by_lid = 0", $lid, $kuid);
      $this->execute();
      return $this->affectedRows() > 0;
    }
    
    function k_clear_editlocks_by_lid($lid) {
      $this->sql("UPDATE kurs SET editlocked_by_lid = 0 WHERE editlocked_by_lid = %d", $lid);
      $this->execute();
    }
    function k_clear_all_editlocks() {
      $this->sql("UPDATE kurs SET editlocked_by_lid = 0 ");
      $this->execute();
    }
    
    
    function get_ordered_list() {
      $this->sql("SELECT {$this->idcol}, CONCAT('[', schulform, stufe, '] ', jahr, '-', hj) AS descr, archiviert FROM {$this->table} ORDER BY schulform,stufe,jahr,hj");
      return $this->getlist();
    }
    /*
    function get_by_id($id) {
      $resultSet = parent::get_by_id($id);
      if (!$resultSet) {
        $this->template_vars["Inhalt"] = 
                  get_view("error_no_datei_selected", array());
      
        $this->display_layout();
        
        exit;
      }
      return $resultSet;
    }
    */
    function set($did, $jahr, $hj, $schulform, $stufe, $archiviert) {
      if ($did === null) {
        parent::sql("INSERT INTO {$this->table} (jahr, hj, schulform, stufe, archiviert)
            values ('%s', '%s', '%s', '%s', %d)",
            $jahr , $hj , $schulform , $stufe , $archiviert);
      } else {
        parent::sql("UPDATE {$this->table} SET
            jahr = '%s', hj = '%s', schulform = '%s', stufe = '%s', archiviert = %d WHERE did = %d",
            $jahr , $hj , $schulform , $stufe , $archiviert , $did);
      }
      return parent::execute();
    }
    
  }
  
?>