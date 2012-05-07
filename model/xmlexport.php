<?php
  /**
   * Model for XML file export for offline editing
   * 
   * @package    NotenDB2
   * @subpackage Models
   * @author     Max Weller <max.weller@wikilab.de>
   **/
	
  class XmlexportModel extends Model {
    
    public function export_datei($did, $lehrerId) {
      $Kurs = load_model("kurs");           $Kurs->DID = $did;
      $Datei = load_model("datei");
      $Schueler = load_model("schueler");   $Schueler->DID = $did;
      $SchuelerKurs = load_model("rel_schueler_kurs");
      $Lehrer = load_model("lehrer");
      
      $datei = $Datei->get_by_id($did);
      $Q = "";
      $Q.='<datei did="' . $did . '">' . "\n";
      $Q.='  <info>' . "\n";
      $Q.='    <dateiversion>2</dateiversion>' . "\n";
      $Q.='    <jahr>' . $this->xmlentities($datei['jahr']) . '</jahr>' . "\n";
      $Q.='    <hj>' . $this->xmlentities($datei['hj']) . '</hj>' . "\n";
      $Q.='    <schulform>' . $this->xmlentities($datei['schulform']) . '</schulform>' . "\n";
      $Q.='    <stufe>' . $this->xmlentities($datei['stufe']) . '</stufe>' . "\n";
      $Q.='  </info>' . "\n";
      $Q.='  <schuelerliste>' . "\n";
      $schueler = $Schueler->get_all();
      foreach($schueler as $d) {
      $Q.='    <schueler sid="' . $d['sid'] . '">' . "\n";
      $Q.='      <name>' . $this->xmlentities($d['name']) . '</name>' . "\n";
      $Q.='      <vorname>' . $this->xmlentities($d['vorname']) . '</vorname>' . "\n";
      $Q.='    </schueler>' . "\n";
      }
      $Q.='  </schuelerliste>' . "\n";
      $Q.='  <kurse>' . "\n";
      if ($lehrerId)
        $kurse = $Kurs->get_by_lid_with_lehrer_namen($lehrerId);
      else
        $kurse = $Kurs->get_all_with_lehrer_namen();
      foreach($kurse as $d) {
      $Q.='    <kurs kuid="' . $d['kuid'] . '">' . "\n";
      $Q.='      <name>' . $this->xmlentities($d['name']) . '</name>' . "\n";
      $Q.='      <art>' . $this->xmlentities($d['art']) . '</art>' . "\n";
      $Q.='      <lehrer>' . $this->xmlentities($d['lehrer_namen']) . '</lehrer>' . "\n";
      $rels = $SchuelerKurs->getAllByRId($d['kuid']);
      foreach($rels as $rel) {
      $Q.='      <note sid="' . $rel['r_sid'] . '" note="' . $this->xmlentities($rel['note']) . '" fehlstunden="' . $this->xmlentities($rel['fehlstunden']) . '" unentschuldigt="' . $this->xmlentities($rel['fehlstunden_un']) . '" />' . "\n";
      }
      $Q.='    </kurs>' . "\n";
      }
      $Q.='  </kurse>' . "\n";
      $Q.='</datei>' . "\n";
      
      
      $filename = $datei['jahr'] . '_' . $datei['hj'] . '_' . $datei['schulform'] . '_' . $datei['stufe'];
      if ($lehrerId) {
        $lehrerInfo = $Lehrer->get_by_id($lehrerId);
        $filename .= '_' . $lehrerInfo['kuerzel'];
      }
      return array($filename, $Q);
    }
    
    function xmlentities ($s) {
      $result = '';
      $len = strlen($s);
      for ($i = 0; $i < $len; $i++) {
        if ($s{$i} == '&') {
          $result .= '&amp;';
        } else if ($s{$i} == '<') {
          $result .= '$lt;';
        } else if ($s{$i} == '>') {
          $result .= '&gt;';
        } else if ($s{$i} == '\'') {
          $result .= '&apos;';
        } else if ($s{$i} == '"') {
          $result .= '&quot;';
        } else if (ord($s{$i}) > 127) {
          // skipping UTF-8 escape sequences requires a bit of work
          if ((ord($s{$i}) & 0xf0) == 0xf0) {
            $result .= $s{$i++};
            $result .= $s{$i++};
            $result .= $s{$i++};
            $result .= $s{$i};
          } else if ((ord($s{$i}) & 0xe0) == 0xe0) {
            $result .= $s{$i++};
            $result .= $s{$i++};
            $result .= $s{$i};
          } else if ((ord($s{$i}) & 0xc0) == 0xc0) {
            $result .= $s{$i++};
            $result .= $s{$i};
          }
        } else {
          $result .= $s{$i};
        }
      }
      return $result;
    }
    
  }
  
?>