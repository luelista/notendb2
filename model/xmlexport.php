<?php
  /**
   * Model for export methods
   * 
   * @package    NotenDB2
   * @subpackage Models
   * @author     Max Weller <max.weller@wikilab.de>
   **/
	
  class XmlexportModel extends Model {
    
    
    
    /**
     * Prepare TEX input file for pdflatex command
     * Replaces Placeholders with actual content from $data
     * 
     * @param   src    Template source file
     * @param   temp   Temporary .tex file to create
     * @param   glob   Global Template placeholders
     * @param   data   Seitenweise Platzhalter
     **/
    public function preprocTexfile($src, $temp, $glob, $data) {
      $lines=explode("\"\r\n\"", substr($data, 1, -1));
      
      $glob['\\tpl{template_name}'] = "$src"; 
      $glob['\\tpl{datetime}'] = date("r");
      $glob['\\tpl{user_kuerzel}'] = load_model("session")->getUser('kuerzel'); 
      $glob['\\tpl{count}'] = count($lines)-1;
      
      $headers=explode('";"', $lines[0]);
      $vorlage=explode("%TPL-REPETITION-MARK", str_replace(array_keys($glob), array_values($glob), file_get_contents($src)));
      $out=fopen($temp, 'w');
      fputs($out, $vorlage[0]);
      for($i=1; $i<count($lines); $i++) {
        $replacement = explode('";"', $lines[$i]);
        $page=$vorlage[1];
        for($c=0; $c<count($replacement); $c++) {
          $page=str_replace('\\tpl{'.$headers[$c].'}', $replacement[$c], $page);
        }
        fputs($out, "\n%--------- Page $i ---------------\n\n$page");
      }
      fputs($out, $vorlage[2]);
      fclose($out);
    }
    
    public function pdflatex($tempName, $save) {
      shell_exec("cd \"".ROOT."/temp\"; TEXINPUTS=\"".ROOT."/content/:$TEXINPUTS\" pdflatex -interaction=batchmode -output-directory=\"".ROOT."/temp\" \"$tempName\"");
      
      if (!$save)
        header("Content-disposition: inline");
      else
        header("Content-disposition: attachment; filename=\"$save.pdf\"");
      
      header("Content-Type: application/pdf");
      readfile(substr($tempName, 0, -4).".pdf");
      unlink(substr($tempName, 0, -4).".pdf");
    }
    
    
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