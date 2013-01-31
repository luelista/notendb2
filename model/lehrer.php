<?php
  /**
   * Data Model for "Lehrer" table
   * 
   * @package    NotenDB2
   * @subpackage Models
   * @author     Moritz Willig <>
   **/
	
  class LehrerModel extends DatabaseModel {
    
    var $table = "lehrer";
    var $idcol = "lid";
    var $structure = array(
             "kuerzel" => "'%s'",
             "anrede" => "'%s'",
             "titel" => "'%s'",
             "name" => "'%s'",
             "vorname" => "'%s'",
             "password" => "'%s'",
             "geburtsdatum" => "'%s'",
             "is_admin" => "%d",
             "kommentar" => "'%s'"
        );
    
    /**
     * check if username is a valid username and if the POST'ed
     * password is correct for the supplied username
     **/
    function check_login($username) {
      $entered_password_hash = "";
      
      // -> check if user is root and handle differently because root password is 
      //    not written to database for security reasons
      if ($username == "root") {
        if ($_POST["password"] == LOGIN_ROOTPASW) {
          return array("kuerzel" => "root", "is_admin" => 1, "lid" => 0);
        } else {
          return false;
        }
      }
      
      // on login, check the entered password
      if (isset($_POST["password"])) {
        $entered_password_hash = $this->hash($_POST["password"], 1, $username);
      } else
      
      // on password change, check the previous password
      if (isset($_POST["previous_password"])) {
        $entered_password_hash = $this->hash($_POST["previous_password"], $username);
      } else
      
      // if no password was supplied, return false!
      {
        return false;
      }
      
      $correct_password_hash = $this->getsingle("SELECT password FROM {$this->table} WHERE kuerzel = '%s' ", $username);
      trace($correct_password_hash, $entered_password_hash);
      
      if ($correct_password_hash == $entered_password_hash) {
        return $this->get("SELECT * FROM {$this->table} WHERE kuerzel = '%s' ", $username);
      } else {
        return false;
      }
    }
    
    function set_password($lid, $password) {
      $this->sql("SELECT kuerzel FROM {$this->table} WHERE lid = %d", $lid);
      $username = $this->getsingle();
      #var_dump($username);
      $this->sql("UPDATE {$this->table} SET password = '%s' WHERE lid = %d",
        $this->hash($password, 1, $username), $lid);
      return $this->execute();
    }
    
    
    
  }
  
?>