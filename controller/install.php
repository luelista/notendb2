<?php
  /**
   * Controller for installing the notendb system
   * 
   * @package    NotenDB2
   * @subpackage Controllers
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
   **/
	
  class InstallController extends Controller {
    var $DB;
    var $Datei;
    var $Lehrer;
    var $Tutor;
    
    /**
     * should be ALWAYS disabled for security reasons!!!
     */
    var $ENABLED = false;
    
    /**
     * Only teachers with admin rights are allowed to use this controller.
     */
    function __construct() {
      parent::__construct();
      
      $this->DB = load_model("database");
      
      $this->Datei = load_model("datei");
      
      $this->Lehrer = load_model("lehrer");
      
      $this->Tutor = load_model("tutor");
      
    }
    
    
    //Lehrer
    
    
    function create_admin() {
      if (!$this->ENABLED) return;
      if ($myID = $this->Lehrer->getsingle("SELECT lid FROM lehrer WHERE kuerzel = '%s'", "ADMIN")) {
        
        
      } else {
        $set = array_fill_keys(array_keys($this->Lehrer->structure), "");
        $set["kuerzel"] = "ADMIN";
        $set["is_admin"] = 1;
        $this->Lehrer->set(null, $set);
        
        $myID = $this->Lehrer->insertId();
      }
      $this->Lehrer->set_password($myID, "54tzck23");
      
      $this->template_vars["Inhalt"] .= "ADMIN user created";
      $this->display_layout();
    }
    
    
  }
  
?>