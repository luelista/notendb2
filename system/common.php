<?php
  /**
   * Common functions
   * 
   * @package    MVC_Framework
   * @subpackage Common
   * @author     Max Weller <max.weller@teamwiki.net>
   **/
  
  // (c) TeamWiki.net
  
  /**
   * shorthand for htmlentities with correct params
   **/
  function entities($t) {
    return htmlentities($t, ENT_COMPAT, "utf-8");
  }
  
  /**
   * get the number of files and folders from a directory
   **/
  function get_file_count($dir) {
    if (!is_dir($dir)) return 0;
    $i = 0; $dh=opendir($dir);
    while($f=readdir($dh))if($f!="."&&$f!="..")$i++;
    closedir($dh); return $i;
  }
  
  /**
   * check with a simple regex if the provided string is a valid mail address
   **/
  function is_valid_email($txt) {
    return preg_match("/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/", $txt);
  }
  
  /**
   * dumps an array of arrays as HTML Table
   **/
  function dump_table($tab,$headers=null) {
    if (is_array($tab)==false || count($tab)==0) {echo "<br><b>";var_dump($tab);echo "</b><br>";return;}
    if(is_array($tab[0])) {
      echo "<table><tr>";
      echo "<th>KEY</th>";
      if(!$headers)$headers = array_keys($tab[0]);
      foreach($headers as $k) echo "<th>$k</th>";
      echo "</tr>";
      foreach($tab as $k=>$row) {
        echo "<tr><td>$k</td>";
        foreach($row as $v) echo "<td>$v</td>";
        echo "</tr>";
      }
      echo "</table>";
    } else {
      echo "<table>";
      foreach($tab as $k=>$v) echo "<tr><th>$k</th><td>$v</td></tr>";
      echo "</table>";
    }
  }
  
  /**
   * converts a string value for the specified boolean
   *
   * @return string  "true" or "false"
   **/
  function bool2str($bool) {
    return $bool ? 'true' : 'false';
  }
  
  /**
   * make sure that no GET and POST parameters get registered as globals
   * by PHP to avoid security loopholes
   *
   * @return  boolean  true if register_globals was enabled before, false if
   *                   register_globals was not enabled and no work was done
   **/
  function disable_register_globals() {
    if (ini_get("register_globals") === false) { // if register_globals is disabled in php.ini,
      return; // everything's fine :-)
    }
    
    $inputVars = array($_GET, $_POST, $_COOKIE);
    foreach($inputVars as $inputVar) {
      foreach($inputVar as $key => $value) {
        // check if var has been set as global and has the 'right' value
        if (isset($GLOBALS[$key]) && $GLOBALS[$key] == value) 
          unset($GLOBALS[$key]); // ... then unset it!
      }
    }
  }
  disable_register_globals();
  
  /**
   * returns true if haystack contains needle
   * @return boolean
   **/
  function instring($haystack, $needle) {
    $res = strpos($haystack, $needle);
    if ($res === false) return false; else return true;
  }
  
  /**
   * format a file size, supplied in bytes, as Byte, KB, MB or GB
   * @return string
   **/
  function format_size ($dsize) {
    $len = strlen($dsize);
    if ($len <= 9 && $len >= 7) {
      $dsize = number_format($dsize / 1048576,1);
      return "$dsize MB";
    } elseif ($len >= 10) {
      $dsize = number_format($dsize / 1073741824,1);
      return "$dsize GB";
    } elseif ($len >= 4) {
      $dsize = number_format($dsize / 1024,1);
      return "$dsize KB";
    } else {
      $dsize = number_format($dsize);
      return "$dsize  B";
    }
  }
  
  /**
   * Converts a HTML hex color value to an array of R,G,B values
   * @return array
   **/
  function html2rgb($color) {
      if ($color[0] == '#')
          $color = substr($color, 1);
  
      if (strlen($color) == 6)
          list($r, $g, $b) = array($color[0].$color[1],
                                   $color[2].$color[3],
                                   $color[4].$color[5]);
      elseif (strlen($color) == 3)
          list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
      else
          return false;
  
      $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
  
      return array($r, $g, $b);
  }
  
  /**
   * Converts an array of R,G,B values to a HTML hex color value
   * @return string  starting with "#"
   **/
  function rgb2html($r, $g=-1, $b=-1) {
      if (is_array($r) && sizeof($r) == 3)
          list($r, $g, $b) = $r;
  
      $r = intval($r); $g = intval($g);
      $b = intval($b);
  
      $r = dechex($r<0?0:($r>255?255:$r));
      $g = dechex($g<0?0:($g>255?255:$g));
      $b = dechex($b<0?0:($b>255?255:$b));
  
      $color = (strlen($r) < 2?'0':'').$r;
      $color .= (strlen($g) < 2?'0':'').$g;
      $color .= (strlen($b) < 2?'0':'').$b;
      return '#'.$color;
  }
  
  /**
   * check the user agent if it is a mobile browser
   * 
   * @return boolean true, if HTTP_USER_AGENT is a mobile browser
   **/
  function check_mobile() {
    $agents = array(
      'Windows CE', 'Pocket', 'Mobile',
      'Portable', 'Smartphone', 'SDA',
      'PDA', 'Handheld', 'Symbian',
      'WAP', 'Palm', 'Avantgo',
      'cHTML', 'BlackBerry', 'Opera Mini',
      'Nokia'
    );
  
    // Prüfen der Browserkennung
    for ($i=0; $i<count($agents); $i++) {
      if(isset($_SERVER["HTTP_USER_AGENT"]) && strpos($_SERVER["HTTP_USER_AGENT"], $agents[$i]) !== false)
        return true;
    }
  
    return false;
  }
  
  
?>