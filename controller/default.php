<?php
  /**
   * Default controller, displays welcome page for logged-in users
   * 
   * @package    NotenDB2
   * @subpackage Controllers
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
   **/
	
  class DefaultController extends Controller {
    var $DB;
    var $Datei;
    
    function __construct() {
      parent::__construct();
      
      $this->require_login();
      
      $this->DB = load_model("database");
      
      $this->Datei = load_model("datei");
      
      $this->template_vars["Dateien"] = $this->Datei->get_ordered_list();
      
    }
    
    /**
     * Willkommensseite mit Dateiauswahl
     **/
    function index() {
      $dateien = $this->Datei->get_all("jahr DESC, hj DESC, Stufe DESC");
      for ($i=0; $i<count($dateien); $i++) { $dateien[$i]["tutor"]=$this->Session->isTutor($dateien[$i]["did"]); }
      
      $this->template_vars["Inhalt"] = 
                  get_view("welcome", array("Dateien" => $dateien));
      
      $this->display_layout();
    }
    
  }
  
?>