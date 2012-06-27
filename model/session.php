<?php
  /**
   * Session management model
   * 
   * @package    NotenDB2
   * @subpackage Models
   * @author     Moritz Willig <>
   **/
	
  class SessionModel {
    
    /**
     * Initializes Session
     **/
    function __construct() {
      ini_set("session.name", "notendbSID");
      session_start();
    
      if (!$this->isSessionInit()) {
      $_SESSION["user"]=null;
      $_SESSION["isLoggedIn"]=false;
      $_SESSION["isSessionInit"]=true;
      }
    }
    
    /**
     * Checks if user session is initialized
     **/
    function isSessionInit() {
      return $_SESSION["isSessionInit"];
    }
    
    /**
     * setLoggedIn alias for loggin out user
     **/
    function getLoggedIn() {
      return $_SESSION["isLoggedIn"];
    }
    
    /**
     * returns session user
     **/
    function getUser($var = null) {
      if ($var)
      return $_SESSION["user"][$var];
      else return $_SESSION["user"];
    }
    
    function isRoot() {
      return $_SESSION["user"]["kuerzel"] == "root";
    }
    
    function isAdmin() {
      return $this->isRoot() || $_SESSION["user"]["is_admin"];
    }
    
    function isTutor($did, $lid = null) {
      $DB = load_model("database");
      if ($lid == null) $lid = $this->getUID();
      return $this->isRoot() || (1 == $DB->getsingle("SELECT COUNT(*) FROM tutor WHERE r_lid = %d AND r_did = %d", $lid, $did));
    }
    
    function getUID() {
      return $_SESSION["user"]["lid"];
    }
    
    /**
     * Sets loggedin state
     **/
    function setLoggedIn($user,$login=true) {
      if ($login) {
      $_SESSION["user"]=$user;
      $_SESSION["isLoggedIn"]=true;
      } else {
      $_SESSION["user"]=null;
      $_SESSION["isLoggedIn"]=false;
      }
    }
    
    /**
     * setLoggedIn alias for loggin out user
     **/
    function logout() {
      $this->setLoggedIn(null,false);
    }
  }

?>