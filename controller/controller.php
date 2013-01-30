<?php
  /**
   * Abstract base class for Controllers
   * 
   * @package    MVC_Framework
   * @subpackage Abstracts
   * @author     Max Weller <max.weller@teamwiki.net>
   **/
	
  class Controller {
    
    var $template_vars = array();
    var $Session;
    var $DID;
    var $archiv = false;
    
    function __construct() {
      $this->Session = load_model("session");
      
      $this->template_vars["ScriptInfo"] = array("LoggedInUser" => $this->Session->getUid());
      
      $this->DID = isset($_GET["datei"]) && intval($_GET["datei"]) > 0 ? intval($_GET["datei"]) : -1;
      set_view_var("DID", $this->DID);
      set_view_var("IsAdmin", $this->Session->isAdmin());
      
      $user = $this->Session->getUser();
      $this->template_vars["Benutzername"] = $user["kuerzel"];
      
      $this->load_menu();
    }
    
    private function k_clear_editlocks_by_lid($lid) {
      $db = load_model("database");
      $db->sql("UPDATE kurs SET editlocked_by_lid = 0 WHERE editlocked_by_lid = %d", $lid);
      $db->execute();
    }
    
    function load_menu() {
      $this->template_vars["Main_Menu"] = array(
        "kurs/view"                   => "<b>1. </b>Liste der Kurse",
        "tabelle/zuordnung_view"      => "<b>2. </b>Zuordnung",
        "tabelle/noten_view"          => "<b>3. </b>Notenvergabe",
        
        "schueler/view"               => "Schülerliste",
        "tabelle/zeugnis"             => "Zeugnisdruck",
        // --temporarilyDisabled-- "offline/info"          => "Offline"
      );
    }
    
    function die_with_error($errmes) {
      $this->template_vars["Inhalt"] = 
                  get_view("error_simple", array("Errmes" => $errmes));
      
      $this->display_layout();
      
      exit;
    }
    
    function display_layout() {
      load_view("layout", $this->template_vars);
    }
    
    function require_login() {
      if (! $this->Session->getLoggedIn()) {
        header("Location: ".URL_PREFIX."user/login");
        exit;
      }
      
      $this->k_clear_editlocks_by_lid($this->Session->getUid());
    }
    
    function require_datei() {
      $DATEI = load_model("datei");
      $datei = null;
      if ($this->DID > -1) {
        $datei = $DATEI->get_by_id($this->DID);
      }
      if ($datei == null) {
        $this->template_vars["Inhalt"] = 
                  get_view("error_no_datei_selected", array());
        $this->display_layout();
        
        exit;
      }
      
      $this->template_vars["ScriptInfo"]["Datei"] = $datei;
      set_view_var("archiv", $this->archiv = $datei['archiviert']);
    }
    
  }
  
?>