<?php
  /**
   * Controller to import and export data for offline editing (disabled)
   * 
   * @package    NotenDB2
   * @subpackage Controllers
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
   **/
	
  class OfflineController extends Controller {
    var $DB;
    var $Datei;
    
    function __construct() {
      parent::__construct();
      $this->require_login();
      
      $this->DB = load_model("database");
      $this->Datei = load_model("datei");
      
      $this->template_vars["Dateien"] = $this->Datei->get_ordered_list();
    }
    
    function info() {
      $this->require_datei();
      
      if ($this->Session->isTutor($this->DID)) {
        $Lehrer = load_model("lehrer");
        $lehrers = $Lehrer->get_all();
        
        $this->template_vars["Inhalt"] = 
                    get_view("offline_info", array("isTutor" => true, "Lehrer" => $lehrers));
      } else {
        $this->template_vars["Inhalt"] = 
                    get_view("offline_info", array("isTutor" => false, "lehrerId" => $this->Session->getUID()));
      }
      $this->display_layout();
    }
    
    function export($did) {
      $id = intval($did);
      
      $Xmlexport = load_model("xmlexport");
      
      list($filename, $output) = $Xmlexport->export_datei($id, intval($_POST["lehrerId"]));
      
      header("Content-Disposition: attachment; filename=\"$filename.xml\"");
      header("Content-Type: text/xml");
      echo '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
      echo $output;
      
    }
    
    function import() {
		if (!$_FILES["importFile"] || $_FILES["importFile"]["error"] != 0) {
			$this->template_vars["Inhalt"] = "Fehler - Datei konnte nicht geladen werden!";
			$this->display_layout();
			return;
		}
		
      $content = file_get_contents($_FILES["importFile"]["tmp_name"]);
	   
	   if (!preg_match('/<datei did="([0-9]+)">/', $content, $did) || $did[1] != $this->DID) {
	   	$this->template_vars["Inhalt"] = "Fehler - Nicht zutreffende Datei-ID!";
			$this->display_layout();
			return;
	   }
   	
		$SchuelerKurs = load_model("rel_schueler_kurs");   	
   	
   	$noten = 0;
      if (preg_match_all('/<kurs kuid="([0-9]+)">/', $content, &$kursMatches, PREG_OFFSET_CAPTURE)) {
      	for($kurs = 0; $kurs < count($kursMatches[0]); $kurs++) {
      		//echo "<br><hr>KUID=".$kursMatches[1][$kurs][0]."<br>";
      		$offset = $kursMatches[1][$kurs][1]; $end = ($kurs == count($kursMatches[0]) - 1) ? strlen($content) : $kursMatches[1][$kurs + 1][1];
      		while(preg_match('/<note sid="([0-9]+)" note="([^"]*)" fehlstunden="([0-9]+)" unentschuldigt="([0-9]+)" \\/>/', $content, &$noteMatches, PREG_OFFSET_CAPTURE, $offset)) {
					if ($noteMatches[0][1] > $end) break;
      			for($note = 1; $note < count($noteMatches); $note++) {
      				$offset = $noteMatches[$note][1] + 1;
      				//echo "| ".$noteMatches[$note][0];
      			}
      			$SchuelerKurs->setByRel($noteMatches[1][0], $kursMatches[1][$kurs][0], array(
      				"note" => $noteMatches[2][0],
      				"fehlstunden" => $noteMatches[3][0],
      				"fehlstunden_un" => $noteMatches[4][0],
					));
					$noten++;
      			//echo "<br>";
      		} 
      	}
      }
      $this->template_vars["Inhalt"] = "Datenimport erfolgreich. $noten Notendatensätze wurden importiert.";
		$this->display_layout();
		
    }
    
  }
  
?>