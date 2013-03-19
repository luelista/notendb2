<?php
  /**
   * Controller for administrating files and teachers
   * 
   * @package    NotenDB2
   * @subpackage Controllers
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
   **/
	
  class AdminController extends Controller {
    var $DB;
    var $Datei;
    var $Lehrer;
    var $Tutor;
    
    /**
     * Only teachers with admin rights are allowed to use this controller.
     */
    function __construct() {
      parent::__construct();
      $this->require_login();
      
      if (!$this->Session->isAdmin())
        die("no admin rights, sorry");
      
      $this->DB = load_model("database");
      $this->Datei = load_model("datei");
      $this->Lehrer = load_model("lehrer");
      $this->Tutor = load_model("tutor");
      
      $this->template_vars["Dateien"] = $this->Datei->get_ordered_list();
    }
    
    
    /**
     * Display administrative Dashboard
     **/
    function dashboard() {
      
      $this->template_vars["Inhalt"] = 
                  get_view("admin_dashboard", array());
      
      $this->display_layout();
    }
    
    /**
     * Clear all editlocks
     **/
    function clear_editlocks() {
      $this->Datei->k_clear_all_editlocks();
      
      $this->template_vars["Inhalt"] = 
        "<div class='success'>Alle Sperrungen wurden aufgehoben<br><br>
         <input type='button' value='   OK   ' onclick='history.back()'></div>";
      $this->display_layout();
    }
    
    function db_export() {
      header("Content-Type: application/octet-stream");
      header("Content-Disposition: attachment; filename=\"db_export_".date("Y_m_d").".bak\"");
      passthru("mysqldump -u".DB_USER." -p".DB_PASSWORD." -h".DB_HOST." ".DB_NAME." | gzip");
    }
    
    //Dateien
    
    /**
     * List all files
     **/
    function dateien() {
      
      $dateien = $this->Datei->get_all();
      
      $this->template_vars["Inhalt"] = 
                  get_view("admin_dateien_list", array("Dateien" => $dateien));
      
      $this->display_layout();
      
    }
    
    /**
     * Edit the details of a file
     **/
    function datei($did) {
      if ($did == "new") $id = null; else $id = intval($did);
      
      
      if (isset($_POST["e"])) {
        $this->Datei->set($id, $_POST["e"]["jahr"], $_POST["e"]["hj"], 
                               $_POST["e"]["schulform"], $_POST["e"]["stufe"],
                               $_POST["e"]["archiviert"]);
        
        $this->Tutor->deleteAllByDid($id);
        if (is_array($_POST["tutor_list"]))
            foreach($_POST["tutor_list"] as $d) $this->Tutor->addByRel($id, $d);
      }
      
      
      if ($id === null) {
        $datei = array_fill_keys($this->Datei->structure, "");
        $lehrer = $this->Lehrer->get_all("name,vorname");
      } else {
        $datei = $this->Datei->get_by_id($id); unset($datei["did"]);
        $this->DB->sql("SELECT vorname,name,lid,r_did 
            FROM lehrer AS l LEFT OUTER JOIN tutor AS t ON t.r_lid=l.lid AND r_did=%d 
            WHERE r_did=%d OR isnull(r_did) ORDER BY name,vorname", $id, $id);
        $lehrer = $this->DB->getlist();
      }
      
      $this->template_vars["Inhalt"] = 
                     get_view("admin_datei_edit", array(
                      "Data" => $datei, "Error" => false,
                      "MethodURL" => "admin/datei/$did",
                      "Lehrer" => $lehrer
                     ));
      
      $this->display_layout();
      
    }
    
    
    
    //Lehrer
    
    /**
     * List all user records of teachers
     **/
    function lehrer_list() {
      
      $dateien = $this->Lehrer->get_all("name,vorname");
      
      $this->template_vars["Inhalt"] = 
                  get_view("admin_lehrer_list", array("Lehrer" => $dateien));
      
      $this->display_layout();
      
    }
    
    /**
     * Edit the user record of a teacher
     **/
    function lehrer($did) {
      if ($did == "new") $id = null; else $id = intval($did);
      
      if (isset($_POST["e"])) {
        $set = $_POST["e"];
        unset($set["password"]);
        $this->Lehrer->set($id, $set);
      }
      
      if ($id === null) {
        $lehrer = array_fill_keys(array_keys($this->Lehrer->structure), "");
      } else {
        $lehrer = $this->Lehrer->get_by_id($id);
      }
      unset($lehrer["lid"]); unset($lehrer["lastlogin"]); 
      unset($lehrer["lastlogin_from"]); unset($lehrer["password"]);
      
      $this->template_vars["Inhalt"] = 
                     get_view("simple_form", array("Data" => $lehrer, "Error" => false, 
                                                   "MethodURL" => "admin/lehrer/$did"));
      
      $this->display_layout();
      
    }
    
    
    /**
     * Import all teachers from file
     **/
    function lehrer_import() {
      if ((isset($_FILES["file"])) and ($_FILES["file"]["error"]==0)) {
        if (file_exists($_FILES["file"]["tmp_name"])) {
          try {
            $file=file_get_contents($_FILES["file"]["tmp_name"]);
            $cont=array_slice(explode("\n",$file),1); //Remove Header Line
            $ct=0;
            foreach ($cont as $s) {
              $s=explode(":",trim($s));
              if (count($s)==5) {
                $this->Lehrer->set(null,array(
                            "name" => $s[1],
                            "vorname" => $s[2],
                            "kuerzel" => $s[0],
                            "geburtsdatum" => $s[3],
                            "password" => $this->DB->hash("54tzck23", 1, $s[0])
                            )
                    );
                $ct++;
              }
            }
            $this->template_vars["Inhalt"] = "<h3>Success!</h3>".$ct." Lehrer wurden importiert";
            $this->display_layout();
            return;
          } catch (Exception $e) {
            $this->template_vars["Inhalt"] = "<h3>Fehler beim Import</h3>Datei beschädigt<p> ($e)";
            $this->display_layout();
            return;
          }
        }
      } else {
        $this->template_vars["Inhalt"] = "<h3>Fehler beim Import</h3>Datei beschädigt";
        $this->display_layout();
        return;
      }
    }
    
    /**
     * Substitute User (for debugging reasons)
     **/
    function su($lehrerID) {
      $Lehrer = load_model("lehrer");
      $lehrerInfo = $Lehrer->get_by_id($lehrerID);
      $this->Session->setLoggedIn($lehrerInfo, true);
      
      header("Location: ".URL_PREFIX."");
      exit;
    }
    
    /**
     * Change/reset the password of another user
     **/
    function set_password($lehrerID) {
      $this->require_login();
      $lehrerID = intval($lehrerID);
      
      $Lehrer = load_model("lehrer");
      $lehrerInfo = $Lehrer->get_by_id($lehrerID);
      
      $template_vars = array("Error" => false, "MethodURL" => "admin/set_password/$lehrerID",
                "InfoText" => "Passwort ändern für ".$lehrerInfo["kuerzel"]    
                ."<script>$(function(){\$('[name=password]').attr('disabled',true);});</script>");
      
      if (isset($_POST["new_password_1"])) {
        if (strlen($_POST["new_password_1"]) >= 7) {
          if ($_POST["new_password_1"] == $_POST["new_password_2"]) {
            $Lehrer->set_password($lehrerID, $_POST["new_password_1"]);
            $this->template_vars["Inhalt"] = "Das Passwort wurde erfolgreich geändert.";
          } else {
            $template_vars["Error"] = "Passwort und Wiederholung müssen übereinstimmen. 
                                       Das Passwort wurde nicht geändert.";
          }
        } else {
          $template_vars["Error"] = "Das Passwort muss aus Sicherheitsgründen min. 7 Zeichen
                                     lang sein. Das Passwort wurde nicht geändert.";
        }
      }
      
      $this->template_vars["Inhalt"] .= get_view("change_password", $template_vars);
      $this->display_layout();
    }
    
    
  }
  
?>
