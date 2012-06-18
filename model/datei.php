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
          "jahr", "hj", "schulform", "stufe"
      );
    
    function get_ordered_list() {
      $this->sql("SELECT {$this->idcol}, CONCAT('[', schulform, stufe, '] ', jahr, '-', hj) AS descr FROM {$this->table} ORDER BY schulform,stufe,jahr,hj");
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
    function set($did, $jahr, $hj, $schulform, $stufe) {
      if ($did === null) {
        parent::sql("INSERT INTO {$this->table} (jahr, hj, schulform, stufe)
            values ('%s', '%s', '%s', '%s')",
            $jahr , $hj , $schulform , $stufe);
      } else {
        parent::sql("UPDATE {$this->table} SET
            jahr = '%s', hj = '%s', schulform = '%s', stufe = '%s' WHERE did = %d",
            $jahr , $hj , $schulform , $stufe , $did);
      }
      return parent::execute();
    }
    
  }
  
?>