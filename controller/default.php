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
    
    function index() {
      
      
      $this->template_vars["Inhalt"] = 
                  get_view("welcome", array());
      
      $this->display_layout();
    }
    
  }
  
?>