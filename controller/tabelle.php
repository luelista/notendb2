<?php
  /**
   * Controller for displaying and saving cross tables
   * 
   * @package    NotenDB2
   * @subpackage Controllers
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
   **/
	
  class TabelleController extends Controller {
    var $DB;
    var $Datei;
    var $Schueler;
    var $Kurs;
    var $SchuelerKurs;
    
    /**
     * Allgemeine Initialisierung der Kreutabellenansichten
     **/
    function __construct() {
      parent::__construct();
      
      $this->require_login();
      
      $this->DB = load_model("database");
      
      $this->Datei = load_model("datei");
      
      $this->Kurs = load_model("kurs");
      $this->Kurs->DID = $this->DID;
      
      $this->Schueler = load_model("schueler");
      $this->Schueler->DID = $this->DID;
      
      $this->SchuelerKurs = load_model("rel_schueler_kurs");
      
      $this->template_vars["Dateien"] = $this->Datei->get_ordered_list();
      
      $this->require_datei();
    }
    
    /**
     * Einstellungsdialog, um Filteroptionen für Kreuztabelle festzulegen
     **/
    function einstellungen($target) {
      if ($_POST["ok"]) {
        $_SESSION["TabelleConfig"] = $_POST;
        header("Location: ".URL_PREFIX."tabelle/$target?datei=".$this->DID);
        return;
      }
      
      $loadConfig = array();
      $loadConfig["Config"] = $_SESSION["TabelleConfig"];
      $loadConfig["MethodURL"] = "tabelle/einstellungen/".$target;
      $loadConfig["MeineKurse"] = $this->Kurs->get_by_lid_with_lehrer_namen($this->Session->getUID());
      
      $this->template_vars["Inhalt"] = 
                  get_view("tabelle_anzeige_einst", $loadConfig);
      
      $this->display_layout();
    }
    
    
    
    /**
     * Anzeige einer Tabellenansicht der Zuordnung zwischen Schülern und Kursen
     **/
    function zuordnung_view() {
      $isTutor = $this->Session->isTutor($this->DID);
      
      $schueler = $this->Schueler->get_all();
      
      $kurse = $this->Kurs->get_all_with_lehrer_namen_and_permission($this->Session->getUID());
      
      for($i = 0; $i < count($kurse); $i++) {
        $kurse[$i]["head_lnk"] =
          ($kurse[$i]["lehrer_perm"] > 0 || $isTutor) ?
          URL_PREFIX."tabelle/zuordnung_edit/".$kurse[$i]["kuid"]."?datei=".$this->DID : "";
        
      }
      
      for($i = 0; $i < count($schueler); $i++) {
        $this->DB->sql("SELECT rid,r_kuid FROM rel_schueler_kurs WHERE r_sid = %d", $schueler[$i]['sid']);
        $info = $this->DB->getlist(); #var_dump($info);
        foreach($kurse as $d) {
          $enabled="";
          $enabled="disabled";
          $schueler[$i]['reldata'][$d['kuid']] = '<img src="'.URL_PREFIX.'content/button_cancel.png" title="Nicht Zugeordnet">';
        }
        foreach($info as $d) {
          $enabled="";
          $enabled="disabled";
          $schueler[$i]['reldata'][$d['r_kuid']] = '<img class="checked_'.$d['r_kuid'].'" src="'.URL_PREFIX.'content/button_ok.png" title="Zugeordnet">';
        }
      }
      
      $this->template_vars["Inhalt"] =
                  "<div style='float:right'>Tipp: Klicken Sie auf das Stift-Icon, um Schüler auszuwählen.</div>".
                  get_view("kreuztabelle", array(
                      "Schueler" => $schueler,
                      "Kurse" => $kurse,
                      "ReadOnly" => true,
                      "Heading" => "Kurs - Schüler - Zuordnung",
                      "MethodURL" => "tabelle/zuordnung"
                  ));
      
      $this->display_layout();
    }
    
    
    /**
     * Bearbeiten der Zuordnung der zwischen einem Kurs und den Schülern
     **/
    function zuordnung_edit($kuid) {
      $isTutor = $this->Session->isTutor($this->DID);
      
      $schueler = $this->Schueler->get_all();
      
      $kurse = $this->Kurs->get_by_kuid_with_lehrer_namen($kuid);
      
      if($_POST["rsk_enable"]) {
        foreach($_POST["rsk_enable"] as $sid => $d)
          foreach($d as $DUMMY_kuid => $stat)
            if ($stat == "Add")
              $this->SchuelerKurs->addByRelEmpty($sid, $kuid);
            elseif ($stat == "Del") 
              $this->SchuelerKurs->deleteByRel($sid, $kuid);
        
        header("Location: ".URL_PREFIX."tabelle/zuordnung_view?datei=".$this->DID);
        exit;
      }
      
      $lockSuccess = $this->Datei->k_set_editlock($kuid, $this->Session->getUid());
      if (!$lockSuccess) {
        $this->DB->sql("SELECT anrede,vorname,lehrer.name FROM lehrer INNER JOIN kurs ON lehrer.lid=kurs.editlocked_by_lid WHERE kuid = %d", $kuid);
        $lehrer = $this->DB->get();
        $this->die_with_error("Diese Spalte wird gerade von <b>$lehrer[anrede] $lehrer[vorname] $lehrer[name]</b> bearbeitet und ist daher gesperrt. Bitte versuchen Sie es später erneut. Falls diese Sperrung länger besteht, wenden Sie sich bitte an den gerade bearbeitenden Lehrer oder an einen Administrator.");
      }
      
      for($i = 0; $i < count($schueler); $i++) {
        $this->DB->sql("SELECT rid,r_kuid FROM rel_schueler_kurs WHERE r_sid = %d", $schueler[$i]['sid']);
        $info = $this->DB->getlist(); #var_dump($info);
        foreach($kurse as $d) {
          $enabled="";
          $schueler[$i]['reldata'][$d['kuid']] = '<input type="checkbox" name="rsk_enable['.$schueler[$i]['sid'].']['.$d['kuid'].']" value="Add" '.$enabled.'>';
        }
        foreach($info as $d) {
          $enabled="";
          $schueler[$i]['reldata'][$d['r_kuid']] = '<input type="hidden" name="rsk_enable['.$schueler[$i]['sid'].']['.$d['r_kuid'].']" value="Del"><input type="checkbox" name="rsk_enable['.$schueler[$i]['sid'].']['.$d['r_kuid'].']" value="Keep" checked '.$enabled.'>';
        }
      }
      
      $this->template_vars["Inhalt"] = 
                  get_view("kreuztabelle", array(
                      "Heading" => "Schüler für ".$kurse[0]["art"]." '".$kurse[0]["name"]."' wählen",
                      "Schueler" => $schueler,
                      "Kurse" => $kurse,
                      "MethodURL" => "tabelle/zuordnung_edit/$kuid"
                  ));
      
      $this->display_layout();
    }
    
    
    
    /**
     * forTest
     **/
    function zuordnung_js() {
      
      $schueler = $this->Schueler->get_all();
      $kurse = $this->Kurs->get_all_with_lehrer_namen();
      
      for($i = 0; $i < count($schueler); $i++) {
        $this->DB->sql("SELECT rid,r_kuid FROM rel_schueler_kurs WHERE r_sid = %d", $schueler[$i]['sid']);
        $info = $this->DB->getlist();
        foreach($kurse as $d) $schueler[$i]['rsk_'.$d['kuid']] = 'No';
        foreach($info as $d) $schueler[$i]['rsk_'.$d['r_kuid']] = 'Yes';
        $schueler[$i]['schueler'] = $schueler[$i]['name'].", ".$schueler[$i]['vorname'];
      }
      
      $this->template_vars["Inhalt"] = 
                  get_view("kreuztabelle_js", array(
                      "Schueler" => $schueler,
                      "Kurse" => $kurse
                  ));
      
      $this->display_layout();
    }
    
    
    
    
    /**
     * Anzeige einer Tabellenansicht, um Noten und Fehlstunden der Schüler einzutragen
     **/
    function noten_view() {
      
      $isTutor = $this->Session->isTutor($this->DID);
      
      $kfilter = false;
      if (isset($_COOKIE["noten_viewMode"]) && !isset($_GET["viewMode"])) $_GET["viewMode"] = $_COOKIE["noten_viewMode"];
      if ($_GET["viewMode"] == "alle_Kurse") {
        $schueler = $this->Schueler->get_all();
        $kurse = $this->Kurs->get_all_with_lehrer_namen_and_permission($this->Session->getUID());
      } else if ($_GET["viewMode"] == "Tutorengruppe") {
        $tut_gruppen = $this->Kurs->get_by_lid_with_lehrer_namen($this->Session->getUID());
        $tut_gruppe = isset($_GET["t_grp"]) ? intval($_GET["t_grp"]) : $tut_gruppen[0]["kuid"];
        $schueler = $this->Schueler->get_all_by_kurs($tut_gruppe);
        $kurse = $this->Kurs->get_all_with_lehrer_namen_and_permission($this->Session->getUID());
        $kfilter = array();
      } else {
        $schueler = $this->Schueler->get_all();
        $kurse = $this->Kurs->get_by_lid_with_lehrer_namen($this->Session->getUID());
        for($i = 0; $i < count($kurse); $i++) $kurse[$i]["lehrer_perm"] = 1;
        $_GET["viewMode"] = "meine_Kurse";
      }
      setcookie("noten_viewMode", $_GET["viewMode"], time()+10000, "/");
      /*
      if ($_POST["rsk"]) {
        foreach($_POST["rsk"] as $rid=>$d) {
          $this->SchuelerKurs->set($rid, array("note" => $d["n"], "fehlstunden" => $d["f"], "fehlstunden_un" => $d["u"]));
        }
      }
      */
      for($i = 0; $i < count($kurse); $i++) {
        $kurse[$i]["head_lnk"] =
          ($kurse[$i]["lehrer_perm"] > 0 || $isTutor) ?
          URL_PREFIX."tabelle/noten_edit/".$kurse[$i]["kuid"]."?datei=".$this->DID : "";
        
      }
      
      $schuelerb = array();
      
      for($i = 0; $i < count($schueler); $i++) {
        $this->DB->sql("SELECT rid,r_kuid,note,fehlstunden,fehlstunden_un FROM rel_schueler_kurs WHERE r_sid = %d", $schueler[$i]['sid']);
        $info = $this->DB->getlist();
        $vis = false;//!$_SESSION["TabelleConfig"]["noten_filter_schueler"];
        foreach($kurse as $d) $schueler[$i]['reldata'][$d['kuid']] = '--';
        foreach($info as $d) {
          if ($kfilter !== false) $kfilter[$d['r_kuid']] = 1; $vis=true;
          $schueler[$i]['reldata'][$d['r_kuid']] = 
            '<nobr>'.htmlspecialchars($d['note']).' | <span class=f>'.htmlspecialchars($d['fehlstunden']).'</span> | <span class=u>'.htmlspecialchars($d['fehlstunden_un']).'</span></nobr>';
          
        }
        //if (!$vis) $schueler[$i]['name'].='XX';
        if ($vis) $schuelerb[]=$schueler[$i];
      }
      
      if ($kfilter !== false) {
        $kurseb = array();
        foreach($kurse as $d) if($kfilter[$d['kuid']]) $kurseb[] = $d;
        $kurse = $kurseb;
      }
      
      $this->template_vars["Inhalt"] .= get_view("kreuztabelle_noten_viewselector", array(
                      "TutorengruppenListe" => $tut_gruppen
                      ));
      //$this->template_vars["Inhalt"] .= get_view("kreuztabelle_noten_helptext", array());
      
      
      $this->template_vars["Inhalt"] .=
                  "<div style='float:right;font:status-bar'>Tipp: Klicken Sie auf das Stift-Icon, um Noten zu vergeben.</div>".
                  get_view("kreuztabelle", array(
                      "Schueler" => $schuelerb,
                      "Kurse" => $kurse,
                      "Heading" => "Noten-Übersicht",
                      "ReadOnly" => "true"
                  ));
      
      $checked0=$_POST["display_all"]?"":"checked";
      $checked1=$_POST["display_all"]?"checked":"";
      
      $this->display_layout();
    }
    
    
    /**
     * Anzeige einer Tabellenansicht, um Noten und Fehlstunden der Schüler einzutragen
     **/
    function noten_edit($kuid) {
      $isTutor = $this->Session->isTutor($this->DID);
      
      $schueler = $this->Schueler->get_all();
      $kurse = $this->Kurs->get_by_kuid_with_lehrer_namen($kuid);
      
      if ($_POST["rsk"]) {
        foreach($_POST["rsk"] as $rid=>$d) {
          $this->SchuelerKurs->set($rid, array("note" => $d["n"], "fehlstunden" => $d["f"], "fehlstunden_un" => $d["u"]));
        }
        header("Location: ".URL_PREFIX."tabelle/noten_view?datei=".$this->DID);
        exit;
      }
      
      $lockSuccess = $this->Datei->k_set_editlock($kuid, $this->Session->getUid());
      if (!$lockSuccess) {
        $this->DB->sql("SELECT anrede,vorname,lehrer.name FROM lehrer INNER JOIN kurs ON lehrer.lid=kurs.editlocked_by_lid WHERE kuid = %d", $kuid);
        $lehrer = $this->DB->get();
        $this->die_with_error("Diese Spalte wird gerade von <b>$lehrer[anrede] $lehrer[vorname] $lehrer[name]</b> bearbeitet und ist daher gesperrt. Bitte versuchen Sie es später erneut. Falls diese Sperrung länger besteht, wenden Sie sich bitte an den gerade bearbeitenden Lehrer oder an einen Administrator.");
      }
      
      
      $schuelerb = array();
      for($i = 0; $i < count($schueler); $i++) {
        $this->DB->sql("SELECT rid,r_kuid,note,fehlstunden,fehlstunden_un FROM rel_schueler_kurs WHERE r_sid = %d AND r_kuid = %d", $schueler[$i]['sid'], $kuid);
        $info = $this->DB->getlist();
        $vis = false;
        foreach($kurse as $d) $schueler[$i]['reldata'][$d['kuid']] = '--';
        foreach($info as $d) {
          $vis=true;
          
          $schueler[$i]['reldata'][$d['r_kuid']] = 
            '<nobr><input type="text" name="rsk['.$d['rid'].'][n]" class=n value="'.htmlspecialchars($d['note']).'" maxlength=2>'.
            '<input type="text" name="rsk['.$d['rid'].'][f]" class=f value="'.htmlspecialchars($d['fehlstunden']).'">'.
            '<input type="text" name="rsk['.$d['rid'].'][u]" class=u value="'.htmlspecialchars($d['fehlstunden_un']).'"></nobr>';
          
        }
        //if (!$vis) $schueler[$i]['name'].='XX';
        if ($vis) $schuelerb[]=$schueler[$i];
      }
      
      $this->template_vars["Inhalt"] .= get_view("kreuztabelle_noten_helptext", array());
      
      
      $this->template_vars["Inhalt"] .= 
                  get_view("kreuztabelle", array(
                      "Schueler" => $schuelerb,
                      "Kurse" => $kurse,
                      "MethodURL" => "tabelle/noten_edit/$kuid"
                  ));
      
      $checked0=$_POST["display_all"]?"":"checked";
      $checked1=$_POST["display_all"]?"checked":"";
      
      $this->display_layout();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * Erste Seite des Zeugnisdruckassistenten (Auswahl der Tutorengruppe und Festlegung der Exportpositionen)
     **/
    function zeugnis() {
      $kurse = $this->Kurs->get_all_with_lehrer_namen_by_export_position();
      
      
      $this->template_vars["Inhalt"] = 
                  get_view("zeugnis_wizard_1", array(
                      "Kurse" => $kurse
                  ));
      
      $this->display_layout();
    }
    
    
    /**
     * Zweite Seite des Zeugnisdruckassistenten (Vorschau)
     **/
    function zeugnis_2() {
      $tutorengruppe = $_POST["kuid"];
      
      if (isset($_POST["export_position"])) {
        foreach($_POST["export_position"] as $kuid=>$export_position) {
          $this->DB->sql("UPDATE kurs SET export_position = %d WHERE kuid = %d", $export_position, $kuid);
          $this->DB->execute();
        }
      }
      
      $schueler = $this->Schueler->get_all_by_kurs($tutorengruppe);
      $kurse = $this->Kurs->get_all_with_lehrer_namen_by_export_position();
      
      foreach($kurse as $d) if($d["kuid"] == $tutorengruppe) $TutorengruppeName="$d[name] ($d[lehrer_namen])";
      
      if (count($schueler) == 0||count($kurse) == 0) {
      $this->template_vars["Inhalt"] .= "<h2>Fehler:</h2> Ihre Auswahl hat eine leere Ausgabe ergeben.<p><input type='button' onclick='history.back()' value='   OK   '>";$this->display_layout();return;}
      
      for($i = 0; $i < count($schueler); $i++) {
        $this->DB->sql("SELECT rid,r_kuid,note,fehlstunden,fehlstunden_un FROM rel_schueler_kurs WHERE r_sid = %d", $schueler[$i]['sid']);
        $info = $this->DB->getlist();
        foreach($kurse as $d) $schueler[$i]['reldata'][$d['kuid']] = '--';
        
        $fehlSumme = $unSumme = 0;
        foreach($info as $d) {
          $schueler[$i]['reldata'][$d['r_kuid']] = htmlspecialchars($d['note']);
          $fehlSumme += $d['fehlstunden']; $unSumme += $d['fehlstunden_un'];
        }
        $schueler[$i]['summe'] = "$fehlSumme | $unSumme";
        
      }
      
      //load view
      $this->template_vars["Inhalt"] .= 
                  get_view("zeugnis_wizard_2", array(
                      "Kuid" => $tutorengruppe,
                      "Schueler" => $schueler,
                      "TutorengruppeName" => $TutorengruppeName,
                      "Kurse" => $kurse
                  ));
      
      $this->display_layout();
    }
    
    
    /**
     * Dritte Seite des Zeugnisdruckassistenten (Download der Druckdatei)
     **/
    function zeugnis_3() {
      $tutorengruppe = $_POST["kuid"];
      
      $schueler = $this->Schueler->get_all_by_kurs($tutorengruppe);
      $kurse = $this->Kurs->get_all_with_lehrer_namen_by_export_position();
      
      $POS_COUNT = 30;
      
      $output = "";
      $output .= '"D_jahr";"D_hj";"D_stufe";"Name";"Geburtsdatum";"KOM"';
      for($i = 1; $i <= $POS_COUNT; $i++) $output .= ';"K_'.$i.'_Name";"K_'.$i.'_Art";"K_'.$i.'_WST";"K_'.$i.'_Thema";"K_'.$i.'_Note";"K_'.$i.'_Lehrer";"K_'.$i.'_Reserviert"';
      
      $output .= ';"FEHL";"UN"';
      
      $globPositions = array();
      foreach($kurse as $d) {
        if ($globPositions[$d['export_position']]) $globPositions[$d['export_position']] = null;
        else $globPositions[$d['export_position']] = ';"'.$d['name'].'";"'.$d['art'].'";"'.$d['wochenstunden'].'";"--";"--";"--";"--"';
      }
      
      for($i = 0; $i < count($schueler); $i++) {
        $this->DB->sql("SELECT rid,r_kuid,note,fehlstunden,fehlstunden_un FROM rel_schueler_kurs WHERE r_sid = %d", $schueler[$i]['sid']);
        $info = $this->DB->getlist();
        
        foreach($kurse as $d) {
          $schueler[$i]['reldata'][$d['kuid']][0] = $d['export_position'];
          $schueler[$i]['reldata'][$d['kuid']][1] = ';"'.$d['name'].'";"'.$d['art'].'";"'.$d['wochenstunden'].'";"'.$d['thema'].'"';
          $schueler[$i]['reldata'][$d['kuid']][2] = false;
          $schueler[$i]['reldata'][$d['kuid']][3] = ';"'.$d['lehrer_namen'].'";""';
        }
        $positions = array();
        $fehl = $un = 0;
        foreach($info as $d) {
          $positions[$schueler[$i]['reldata'][$d['r_kuid']][0]] = 
          $schueler[$i]['reldata'][$d['r_kuid']][1] .
            ';"'.sprintf("%02d", $d['note']).'"'.
          $schueler[$i]['reldata'][$d['r_kuid']][3] ;
          $fehl+=$d["fehlstunden"]; $un+=$d["fehlstunden_un"];
        }
        
        $dat = $this->Datei->get_by_id($this->DID);
        $output .= "\r\n" . '"'. $dat["jahr"] .'";"'.$dat["hj"].'";"'.$dat["stufe"].'"';
        $output .= ';"'.$schueler[$i]['vorname'].' '.$schueler[$i]['name'].'";"'.$schueler[$i]['geburtsdatum'].'";"'.$schueler[$i]['kommentar'].'"';
        
        for($p = 1; $p <= $POS_COUNT; $p++) {
          //$output.= "[$j/ $p=".$schueler[$i]['reldata'][$kurse[$j]['kuid']][0]."]";
          //if ($schueler[$i]['reldata'][$kurse[$j]['kuid']][0] == $p) {
          //  $output .= $schueler[$i]['reldata'][$kurse[$j]['kuid']][1].$schueler[$i]['reldata'][$kurse[$j]['kuid']][2];
          //  $j++;
          //} else {
          //  $output .= ',"","","","","",""';
          //}
          if ($positions[$p]) $output .= $positions[$p];
          elseif ($globPositions[$p]) $output .= $globPositions[$p];
          else $output .=  ';"--";"--";"--";"--";"--";"--";"--"';
        }
        
        $output .= ';"'.$fehl.'";"'.$un.'"';
        
      }
      
      if ($_POST["export"]) {
      
        //$this->template_vars["Inhalt"] = "<pre>" . $output . "</pre>";
        //$this->display_layout();
        header("Content-disposition: attachment; filename=\"$_POST[exp_name].csv\"");
        header("Content-Type: text/plain");
        echo $output;
      } elseif ($_POST["export_xls"]) {
        $tempName = "/srv/www/htdocs/notendb2/temp/export.txt";
        $tempName2 = "/srv/www/htdocs/notendb2/temp/export.xls";
        
        file_put_contents($tempName, $output);
        
         shell_exec("java -jar /srv/www/include/MakeMeExcel.jar \"$_POST[exp_name]\" if \"$tempName\" of \"$tempName2\"");
        header("Content-disposition: attachment; filename=\"$_POST[exp_name].xls\"");
        header("Content-Type: application/vnd.ms-excel");
        readfile($tempName2);
      }
    }
    
    
  }
  
?>
