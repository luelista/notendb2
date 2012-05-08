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
    
    function zuordnung() {
      $isTutor = $this->Session->isTutor($this->DID);
      
      $schueler = $this->Schueler->get_all();
      $kurse = $this->Kurs->get_all_with_lehrer_namen_and_permission($this->Session->getUID());
      #var_dump($_POST);
      
      if($_POST["rsk_enable"]) {
        foreach($_POST["rsk_enable"] as $sid => $d)
          foreach($d as $kuid => $stat)
            if ($stat == "Add")
              $this->SchuelerKurs->addByRelEmpty($sid, $kuid);
            elseif ($stat == "Del") 
              $this->SchuelerKurs->deleteByRel($sid, $kuid);
      }
      
      $kursPerms = array();
      foreach($kurse as $d) {
        $kursPerms[$d["kuid"]] = $d["lehrer_perm"] > 0;
      }
      
      for($i = 0; $i < count($schueler); $i++) {
        $this->DB->sql("SELECT rid,r_kuid FROM rel_schueler_kurs WHERE r_sid = %d", $schueler[$i]['sid']);
        $info = $this->DB->getlist(); #var_dump($info);
        foreach($kurse as $d) {
          $enabled="";
          if (!$kursPerms[$d["kuid"]] && !$isTutor) $enabled="disabled";
          $schueler[$i]['reldata'][$d['kuid']] = '<input type="checkbox" name="rsk_enable['.$schueler[$i]['sid'].']['.$d['kuid'].']" value="Add" '.$enabled.'>';
        }
        foreach($info as $d) {
          $enabled="";
          if (!$kursPerms[$d["r_kuid"]] && !$isTutor) $enabled="disabled";
          $schueler[$i]['reldata'][$d['r_kuid']] = '<input type="hidden" name="rsk_enable['.$schueler[$i]['sid'].']['.$d['r_kuid'].']" value="Del"><input type="checkbox" name="rsk_enable['.$schueler[$i]['sid'].']['.$d['r_kuid'].']" value="Keep" checked '.$enabled.'>';
        }
      }
      
      $this->template_vars["Inhalt"] = 
                  get_view("kreuztabelle", array(
                      "Schueler" => $schueler,
                      "Kurse" => $kurse,
                      "MethodURL" => "tabelle/zuordnung"
                  ));
      
      $this->display_layout();
    }
    
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
    
    function noten() {
      //if($all!="alle")$all="meine";
      $isTutor = $this->Session->isTutor($this->DID);
      
      $schueler = $this->Schueler->get_all();
      //if ($all=="alle") {
        $kurse = $this->Kurs->get_all_with_lehrer_namen_and_permission($this->Session->getUID());
      //} else {
      //  $kurse = $this->Kurs->get_by_lid_with_lehrer_namen($this->Session->getUID());
      //}
      
      if ($_POST["rsk"]) {
        foreach($_POST["rsk"] as $rid=>$d) {
          $this->SchuelerKurs->set($rid, array("note" => $d["n"], "fehlstunden" => $d["f"], "fehlstunden_un" => $d["u"]));
        }
      }
      
      $kursPerms = array();
      foreach($kurse as $d) {
        $kursPerms[$d["kuid"]] = $d["lehrer_perm"] > 0;
      }
      
      for($i = 0; $i < count($schueler); $i++) {
        $this->DB->sql("SELECT rid,r_kuid,note,fehlstunden,fehlstunden_un FROM rel_schueler_kurs WHERE r_sid = %d", $schueler[$i]['sid']);
        $info = $this->DB->getlist();
        foreach($kurse as $d) $schueler[$i]['reldata'][$d['kuid']] = '--';
        foreach($info as $d) {
          if ($isTutor || $kursPerms[$d['r_kuid']]) {
            $schueler[$i]['reldata'][$d['r_kuid']] = 
              '<nobr><input type="text" name="rsk['.$d['rid'].'][n]" class=n value="'.htmlspecialchars($d['note']).'" maxlength=2>'.
              '<input type="text" name="rsk['.$d['rid'].'][f]" value="'.htmlspecialchars($d['fehlstunden']).'">'.
              '<input type="text" name="rsk['.$d['rid'].'][u]" value="'.htmlspecialchars($d['fehlstunden_un']).'"></nobr>';
          } else {
            $schueler[$i]['reldata'][$d['r_kuid']] = 
              '<nobr>'.htmlspecialchars($d['note']).' | '.htmlspecialchars($d['fehlstunden']).' | '.htmlspecialchars($d['fehlstunden_un']).'</nobr>';
            
          }
          
        }
        
      }
      
      $this->template_vars["Inhalt"] = 
                  get_view("kreuztabelle", array(
                      "Schueler" => $schueler,
                      "Kurse" => $kurse,
                      "MethodURL" => "tabelle/noten/$all"
                  ));
      
      $checked0=$_POST["display_all"]?"":"checked";
      $checked1=$_POST["display_all"]?"checked":"";
      $this->template_vars["Inhalt"] .= <<<STYLE
      <style>
      .kreuztabelle input { width:16px;border:1px solid #555;;padding:0;border-left-width:0;background:#eee }
      .kreuztabelle input.n { border-left-width:1px;background:#fff }
      </style>
      <br>
      <h3>Hinweise:</h3>
      Die drei Felder in jeder Zelle stehen für: Note, Fehlstunden gesamt, Fehlstunden unentschuldigt.
      <br>
      Nach dem Ausfüllen der Tabelle bitte oben rechts auf "Eingegebene Daten speichern" klicken.
      <br><br>
STYLE;
      
      $this->display_layout();
    }
    
    function zeugnis() {
      $kurse = $this->Kurs->get_all_with_lehrer_namen_by_export_position();
      
      
      $this->template_vars["Inhalt"] = 
                  get_view("zeugnis_wizard_1", array(
                      "Kurse" => $kurse
                  ));
      
      $this->display_layout();
    }
    
    
    function zeugnis_2() {
      $tutorengruppe = $_POST["kuid"];
      
      foreach($_POST["export_position"] as $kuid=>$export_position) {
        $this->DB->sql("UPDATE kurs SET export_position = %d WHERE kuid = %d", $export_position, $kuid);
        $this->DB->execute();
      }
      
      $schueler = $this->Schueler->get_all_by_kurs($tutorengruppe);
      $kurse = $this->Kurs->get_all_with_lehrer_namen_by_export_position();
      
      
      $POS_COUNT = 30;
      
      $output = "";
      $output .= '"D_jahr","D_hj","D_stufe","Name","Geburtsdatum","KOM"';
      for($i = 1; $i <= $POS_COUNT; $i++) $output .= ',"K_'.$i.'_Name","K_'.$i.'_Art","K_'.$i.'_WST","K_'.$i.'_Thema","K_'.$i.'_Note","K_'.$i.'_Lehrer","K_'.$i.'_Reserviert"';
      
      $output .= ',"FEHL","UN"';
      
      for($i = 0; $i < count($schueler); $i++) {
        $this->DB->sql("SELECT rid,r_kuid,note,fehlstunden,fehlstunden_un FROM rel_schueler_kurs WHERE r_sid = %d", $schueler[$i]['sid']);
        $info = $this->DB->getlist();
        
        foreach($kurse as $d) {
          $schueler[$i]['reldata'][$d['kuid']][0] = $d['export_position'];
          $schueler[$i]['reldata'][$d['kuid']][1] = ',"'.$d['name'].'","'.$d['art'].'","'.$d['wochenstunden'].'","'.$d['thema'].'"';
          $schueler[$i]['reldata'][$d['kuid']][2] = false;
          $schueler[$i]['reldata'][$d['kuid']][3] = ',"'.$d['lehrer_namen'].'",""';
        }
        $positions = array();
        $fehl = $un = 0;
        foreach($info as $d) {
          $positions[$schueler[$i]['reldata'][$d['r_kuid']][0]] = 
          $schueler[$i]['reldata'][$d['r_kuid']][1] .
            ',"'.htmlspecialchars($d['note']).'"'.
          $schueler[$i]['reldata'][$d['r_kuid']][3] ;
          $fehl+=$d["fehlstunden"]; $un+=$d["fehlstunden_un"];
        }
        
        $dat = $this->Datei->get_by_id($this->DID);
        $output .= "\r\n" . '"'. $dat["jahr"] .'","'.$dat["hj"].'","'.$dat["stufe"].'"';
        $output .= ',"'.$schueler[$i]['vorname'].' '.$schueler[$i]['name'].'","'.$schueler[$i]['geburtsdatum'].'","'.$schueler[$i]['kommentar'].'"';
        
        for($p = 1; $p <= $POS_COUNT; $p++) {
          //$output.= "[$j/ $p=".$schueler[$i]['reldata'][$kurse[$j]['kuid']][0]."]";
          //if ($schueler[$i]['reldata'][$kurse[$j]['kuid']][0] == $p) {
          //  $output .= $schueler[$i]['reldata'][$kurse[$j]['kuid']][1].$schueler[$i]['reldata'][$kurse[$j]['kuid']][2];
          //  $j++;
          //} else {
          //  $output .= ',"","","","","",""';
          //}
          if ($positions[$p]) $output .= $positions[$p]; else $output .=  ',"","","","","","",""';
        }
        
        $output .= ',"'.$fehl.'","'.$un.'"';
        
      }
      
      $this->template_vars["Inhalt"] = "<pre>" . $output . "</pre>";
      $this->display_layout();
    }
    
    
  }
  
?>