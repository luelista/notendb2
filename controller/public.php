<?php
  /**
   * Controller for managing user data
   * 
   * @package    NotenDB2
   * @subpackage Controllers
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
   **/
	
  class PublicController extends Controller {
    
    var $DB;
    
    function __construct() {
      parent::__construct();
      $this->DB = load_model("database");
    }
    public function abi_vorschau() {
      $q="";
      
      $q.="<form action='".URL_PREFIX."public/abi_vorschau' method='post'>";
      $q.="Username: <input type='text' name='username' value='".htmlentities($_POST['username'])."'> <br>Geburtsdatum: <input type='text' name='birthday' value='".htmlentities($_POST['birthday'])."'><br><br><input type='submit' name='search' value='OK'>";
      $q.="</form>";
      
      if ($_POST["search"]) {
        $schuelers = $this->DB->getlist("SELECT sid,name,vorname,username,geburtsdatum,d.* FROM schueler s INNER JOIN datei d ON s.did=d.did WHERE username LIKE '%s%%' AND Geburtsdatum='%s' ", $_POST['username'], $_POST['birthday']);
        $q.= "<p>Gefundene Datensätze: ".count($schuelers)."</p>";
        $col=0; $fach=array();
        
        $q.='<table><tr><td>/-</td>';
        foreach($schuelers as $d) {
          $q.= "<td>$d[name] $d[vorname] <br>$d[jahr] $d[hj]. Hj $d[stufe]$d[schulform]</td>";
          
          $ff=$this->DB->getlist("SELECT name,note  FROM rel_schueler_kurs rsk INNER JOIN kurs k ON k.kuid=rsk.r_kuid WHERE k.did=%d AND rsk.r_sid=%d",
          $d['did'], $d['sid']);
          foreach($ff as $f) $fach[$f['name']][$col] = $f;
          $col++;
        }
        //var_dump($fach);
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