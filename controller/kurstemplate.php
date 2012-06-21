<?php
  /**
   * Controller for viewing and creating Kurs items
   * 
   * @package    NotenDB2
   * @subpackage Controllers
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
   **/
	
  class KurstemplateController extends Controller {
    var $DB;
    var $Datei;
    var $KursTemplate;
    var $LehrerKurs;
    
    function __construct() {
      parent::__construct();
      
      $this->require_login();
      
      $this->DB = load_model("database");
      
      $this->Datei = load_model("datei");
      
      $this->KursTemplate = load_model("kurs_template");
      
      $this->LehrerKurs = load_model("rel_lehrer_kurs");
      
      $this->template_vars["Dateien"] = $this->Datei->get_ordered_list();
      
      
    }
    
    function loadajax($ktid) {
      $kurs = $this->KursTemplate->get_by_id($ktid);
      echo json_encode(array("template" => $kurs));
    }
    
    function view() {
      //$list = $this->Kurs->get_all();
      $list = $this->KursTemplate->get_all();
      
      $this->template_vars["Inhalt"] = 
                  get_view("kurstemplate_list", array("Liste" => $list));
      
      $this->display_layout();
    }
    
    function edit($kuid) {
      if ($kuid == "new") $id = null; else $id = intval($kuid);
      
      if (isset($_POST["e"])) {
        $set = $_POST["e"];
        $this->KursTemplate->set($id, $set);
        if ($id == null) $id = mysql_insert_id();
        if ($_POST["e"]["saveAndNew"]) $id = null; else $kuid = $id;
      }
      
      if ($id === null) {
        $kurs = array_fill_keys(array_keys($this->KursTemplate->structure), "");
        
      } else {
        $kurs = $this->KursTemplate->get_by_id($id);
        
      }
      unset($kurs["ktid"]);
      
      $this->template_vars["Inhalt"] = 
                     get_view("kurstemplate_edit", array(
                          "Data" => $kurs,
                          "Error" => false,
                          "MethodURL" => "kurstemplate/edit/$kuid"
                     ));
      
      $this->display_layout();
      
    }
  }
  
?>