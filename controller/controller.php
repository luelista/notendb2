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
    
    function __construct() {
      $this->Session = load_model("session");
      
      $this->DID = isset($_GET["datei"]) && intval($_GET["datei"]) > 0 ? intval($_GET["datei"]) : -1;
      set_view_var("DID", $this->DID);
      set_view_var("IsAdmin", $this->Session->isAdmin());
      
      $user = $this->Session->getUser();
      $this->template_vars["Benutzername"] = $user["kuerzel"];
      
      $this->load_menu();
    }
    
    function load_menu() {
      $this->template_vars["Main_Menu"] = array(
        "kurs/view"             => "Liste der Kurse",
        "schueler/view"         => "Schülerliste",
        "tabelle/zuordnung"     => "Zuordnung",
        "tabelle/noten"         => "Notenvergabe",
        "tabelle/zeugnis"       => "Zeugnisdruck",
        // --temporarilyDisabled-- "offline/info"          => "Offline"
      );
    }
    
    function display_layout() {
      load_view("layout", $this->template_vars);
    }
    
    function require_login() {
      if (! $this->Session->getLoggedIn()) {
        header("Location: ".URL_PREFIX."user/login");
        exit;
      }
    }
    
    function require_datei() {
      if ($this->DID == -1 || load_model("datei")->get_by_id($this->DID) == null) {
        $this->template_vars["Inhalt"] = 
                  get_view("error_no_datei_selected", array());
      
        $this->display_layout();
        
        exit;
      }
    }
    
  }
  
?>