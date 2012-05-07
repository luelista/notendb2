<?php
  /**
   * Controller for managing user data
   * 
   * @package    NotenDB2
   * @subpackage Controllers
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
   **/
	
  class UserController extends Controller {
    
    var $DB;
    
    function __construct() {
      parent::__construct();
      $this->DB = load_model("database");
    }
    
    function login() {
      $template_vars = array("Error" => false);
      
      if ($_POST["username"]) {
        $Lehrer = load_model("lehrer");
        if ($user = $Lehrer->check_login($_POST["username"])) {
          $this->Session->setLoggedIn($user, true);
          header("Location: ".URL_PREFIX."");
          exit;
        } else {
          $template_vars["Error"] = "Fehlerhafte Benutzerdaten wurden eingegeben";
        }
      }
      
      load_view("loginform", $template_vars);
    }
    
    function logout() {
      $this->require_login();
      
      $this->Session->logout();
      header("Location: ".URL_PREFIX."user/login");
    }
    
    function change_password() {
      $this->require_login();
      
      $template_vars = array("Error" => false, "MethodURL" => "user/change_password", "InfoText" => "");
      if (isset($_POST["new_password_1"])) {
        if (strlen($_POST["new_password_1"]) >= 7) {
          $Lehrer = load_model("lehrer");
          if ($Lehrer->check_login($this->Session->getUser("kuerzel"))){
            if ($_POST["new_password_1"] == $_POST["new_password_2"]) {
              $Lehrer->set_password($this->Session->getUid(), $_POST["new_password_1"]);
              $this->template_vars["Inhalt"] = "Das Passwort wurde erfolgreich geändert.";
            } else {
              $template_vars["Error"] = "Passwort und Wiederholung müssen übereinstimmen. Das Passwort wurde nicht geändert.";
            }
          } else {
            $template_vars["Error"] = "Das alte Passwort wurde nicht korrekt eingegeben. Das Passwort wurde nicht geändert.";
          }
        } else {
          $template_vars["Error"] = "Das Passwort muss aus Sicherheitsgründen min. 7 Zeichen lang sein. Das Passwort wurde nicht geändert.";
        }
      }
      
      $this->template_vars["Inhalt"] .= get_view("change_password", $template_vars);
      $this->display_layout();
    }
    
  }
  
?>