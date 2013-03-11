<?php
  /**
   * Controller for managing Schueler data
   * 
   * @package    NotenDB2
   * @subpackage Controllers
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
   **/
	
  class SchuelerController extends Controller {
    var $DB;
    var $Datei;
    var $Schueler;
    
    function __construct() {
      parent::__construct();
      $this->require_login();
      
      $this->DB = load_model("database");
      $this->Datei = load_model("datei");
      $this->Schueler = load_model("schueler");
      $this->Schueler->DID = $this->DID;
      $this->template_vars["Dateien"] = $this->Datei->get_ordered_list();
      $this->require_datei();
    }
    
    function view() {
      $list = $this->Schueler->get_all();
      
      $this->template_vars["Inhalt"] = 
                  get_view("schueler_list", array("Liste" => $list, "isTutor" => $this->Session->isTutor($this->DID) || $this->Session->isAdmin()));
      
      $this->display_layout();
    }

    function edit($sid) {
      if ($this->archiv) die("Archivierte Datei - bearbeiten nicht möglich!");
      if ($sid == "new") $id = null; else $id = intval($sid);
      
      if (isset($_POST["e"])) {
        $set = $_POST["e"];
        $this->Schueler->set($id, $set);
      }
      
      if ($id === null) {
        $schueler = array_fill_keys(array_keys($this->Schueler->structure), "");
      } else {
        $schueler = $this->Schueler->get_by_id($id);
      }
      $lastMod = $schueler["updated"];
      unset($schueler["sid"]); unset($schueler["did"]); unset($schueler["updated"]);
      
      $this->template_vars["Inhalt"] = 
                      (isset($_POST["e"]) ? "<div class='success'>Der Schüler wurde erfolgreich gespeichert.</div>":"").
                     get_view("simple_form", array("Data" => $schueler, "Error" => false, "MethodURL" => "schueler/edit/$sid"))
                     .($sid != "new" ? "<br><br>Letzte Änderung: $lastMod<br><br><input type='button' value='Notenübersicht' onclick='location=\"".URL_PREFIX."schueler/noten_uebersicht_pdf/$sid?datei={$this->DID}\";'><br>" : "");
      
      $this->display_layout();
      
    }
	

    function import() {
      if ($this->archiv) die("Archivierte Datei - bearbeiten nicht möglich!");
  		if ((isset($_FILES["file"])) and ($_FILES["file"]["error"]==0)) {
  			if (file_exists($_FILES["file"]["tmp_name"])) {
  				try {
  					$file=file_get_contents($_FILES["file"]["tmp_name"]);
  					$cont=array_slice(explode("\n",$file),1); //Remove Header Line
  					$ct=0;
  					foreach ($cont as $s) {
  						$s=explode(":",trim($s));
  						if (count($s)==5) {
  							$this->Schueler->set(null,array(	"name" => $s[1],
  													"vorname" => preg_replace("/[0-9]+$/", "", $s[2]),
  													"username" => $s[0],
  													"geburtsdatum" => $s[3],
  													"klasse" => $s[4])
  									);
  							$ct++;
  						}
  						}
  					$this->template_vars["Inhalt"] = "<div class='success'><h3>Success!</h3>".$ct." Schüler wurden importiert</div>";
  					$this->display_layout();
  					return;
  				} catch (Exception $e) {
  					$this->template_vars["Inhalt"] = "<h3>Fehler beim Import</h3>Datei beschädigt";
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
  	
  	function noten_uebersicht_pdf($sid) {
    	$schueler = $this->Schueler->get_by_id(intval($sid));
    	list($cols, $schuelers, $fach) =$this->Schueler->get_noten_uebersicht($schueler['username'], $schueler['geburtsdatum']);
    	
    	$q=$morecols=""; $ZS="\r\n";
      $q.='"Fach";"Noten"'.$ZS;
      $q.='"--";"';
      foreach($schuelers as $d) {
        $morecols.="p{2cm}|"; $q.= " & \\pbox{ $d[jahr]  \\ {\\bfseries $d[stufe]$d[schulform] $d[hj]. Hj}}";
      }
      $q.='"';
      
      foreach($fach as $fname=>$fcols) {
        $q.=$ZS.'"'.$fname.'";"';
        for($i=0;$i<$cols;$i++) $q.=' & '.$fcols[$i]['note'].'';
        $q.='"';
      }
      
      $vorlage = ROOT."/content/template_uebersicht.tex";
      $tempName = ROOT."/temp/export.tex";
      $globRepl = array('\\tpl{Name}' => $schuelers[0]['vorname'].' '.$schuelers[0]['name'],
        '\\tpl{morecols}' => $morecols);
      //echo "<pre>$q";
      
      $export = load_model("xmlexport");
      $export->preprocTexfile($vorlage, $tempName, $globRepl, $q);
      $export->pdflatex($tempName, null);
      
    	
  	}
    
  }
  
?>