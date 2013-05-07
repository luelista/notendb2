<?php
  /**
   * Controller for displaying statistics
   * 
   * @package    NotenDB2
   * @subpackage Controllers
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <moritz.willig@gmail.com>
   **/
	
  class StatisticsController extends Controller {
    var $DB;
    var $Datei;
    var $Kurs;
    var $LehrerKurs;
    
    function __construct() {
      parent::__construct();
      $this->require_login();
      
      $this->DB = load_model("database");
      $this->Datei = load_model("datei");
      $this->Kurs = load_model("kurs");
      $this->Kurs->DID = $this->DID;
      $this->LehrerKurs = load_model("rel_lehrer_kurs");
      
      $this->template_vars["Dateien"] = $this->Datei->get_ordered_list();
      $this->require_datei();
    }
    
    function latinum() {
      $this->DB->sql("
      SELECT vorname,s.name,geburtsdatum,klasse,note 
      FROM schueler as s inner join rel_schueler_kurs as rsk ON s.sid=rsk.r_sid INNER JOIN kurs AS k ON r_kuid=kuid
      WHERE k.name LIKE '%%Latein%%' AND k.did='%d' AND note>=5",$this->DID);
      $list=$this->DB->getlist();
      
      header("Content-disposition: attachment; filename=\"latinum.csv\"");
      header("Content-Type: text/plain");
      load_view("statistics_dump", array("list"=>$list,"fach"=>"Latein"));
    }
  }
  
?>