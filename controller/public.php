<?php
  /**
   * Controller for managing user data
   * 
   * @package    NotenDB2
   * @subpackage Controllers
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
   **/
	
  class PublicController extends Controller {
    
    var $DB, $Schueler;
    
    function __construct() {
      parent::__construct();
      $this->DB = load_model("database");
      $this->Schueler = load_model("schueler");
    }
    public function abi_vorschau() {
      $q="";
      
      $q.="<form action='".URL_PREFIX."public/abi_vorschau' method='post'>";
      $q.="Username: <input type='text' name='username' value='".htmlentities($_POST['username'])."'> <br>Geburtsdatum: <input type='text' name='birthday' value='".htmlentities($_POST['birthday'])."'><br><br><input type='submit' name='search' value='OK'>";
      $q.="</form>";
      
      if ($_POST["search"]) {
        list($col, $schuelers, $fach) =$this->Schueler->get_noten_uebersicht($_POST['username'], $_POST['birthday']);
        
        $q.= "<p>Gefundene Datensätze: ".count($schuelers)."</p>";
        $q.='<table><tr><td>/-</td>';
        foreach($schuelers as $d) {
          $q.= "<td>$d[name] $d[vorname] <br>$d[jahr] $d[hj]. Hj $d[stufe]$d[schulform]</td>";
        }
        $q.='</tr>';

        
        $q.='</tr>';
        foreach($fach as $fname=>$fcols) {
          $q.='<tr><td>'.$fname.'</td>';
          for($i=0;$i<=$col;$i++) $q.='<td>'.$fcols[$i]['note'].'</td>';
          $q.='</tr>';
        }
        $q.='</table>';
        
      }
      
      load_view("publiclayout", array("Inhalt" => $q));
      
    }    
  }
  
?>