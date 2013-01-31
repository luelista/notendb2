<?php
  session_start();
  
  define('URL_PREFIX', dirname($_SERVER['PHP_SELF']).'/');
  
  function template($Inhalt) {
    $DocTitle = "Konfigurations"; $SiteTitle = "Assistent";
    include "view/layoutmin.php";
  }
  function write_config($SITE_CONFIG) {
    $f = fopen('.htconfig.php', 'w');
    fputs($f, "<?php\n  // Global configuration file\n  // automatically generated, do not change\n\n");
    foreach($SITE_CONFIG as $k=>$v) {
      fprintf($f, "  \$SITE_CONFIG[%s] = %s;\n", var_export($k, true), var_export($v, true));
    }
    fputs($f, "\n  // End of global configuration file\n");
    fclose($f);
  }
  
  if ((! isset($_SESSION['INI'])) && file_exists('.htconfig.php')) {
    if ($_REQUEST['load']) {
      include '.htconfig.php';
      if (! is_array($SITE_CONFIG)) {
        template('Fehler beim Laden! Bitte löschen Sie die <code>.htconfig.php</code>, um fortzufahren.');
      } elseif ($SITE_CONFIG['LOGIN_ROOTPASW'] == $_POST['pass']) {
        $_SESSION['INI'] = $SITE_CONFIG;
        echo "<script>location='install.php?loaded=true'</script>";
        
      } else {
        template('
        <form action="install.php" method="post">
        <p>Die Konfiguration ist mit einem Passwort geschützt. Bitte geben Sie das Kennwort des Super-Administrators ein, um fortzufahren.</p>
        <p>Passwort: <input type="password" name="pass"></p>
        <p><input type="submit" name="load" value="Konfiguration laden"></p>
        </form>
        ');
      }
    } else {
      
        template('
        <form action="install.php" method="post">
        <p>Es wurde eine bestehende Konfigurationsdatei gefunden. Klicken Sie die untenstehende Schaltfläche an, um diese zu laden. Falls Sie das nicht möchten, oder falls das Laden fehlschlägt, müssen Sie zunächst auf anderem Wege die <code>.htconfig.php</code> löschen.</p>
        <p><input type="submit" name="load" value="Konfiguration laden"></p>
        </form>
        ');
    }
    exit;
  }
  
  $DB_CONN_OK = "cancel";
  $DB_TAB_OK = "cancel";
  $CONFIG_PERM_OK = "cancel";
  $CONFIG_SET_OK = "cancel";
  $INIDONE_OK = "cancel";
  
  if (!$_SESSION["INI"]["DB_HOST"]) $_SESSION["INI"]["DB_HOST"] = "localhost";
  
  if (is_writable(".htconfig.php")) $CONFIG_PERM_OK = "ok";
  
  if ($_POST["logout"]) {
    session_destroy();
    template('<p>Die Konfiguration wurde beendet.</p><p><input type="button" value="Konfiguration erneut öffnen " onclick="location=\'install.php\';"> <input type="button" value="Zur Startseite" onclick="location=\''.URL_PREFIX.'\';"> </p>');
    exit;
  }
  
  if ($_POST["ini"]) {
    foreach($_POST["ini"] as $k=>$v) $_SESSION["INI"][$k] = $v;
    $_SESSION['INIDONE'] = 0;
  }
  
  if ($_SESSION["INI"]["DB_USER"]) {
    if (@mysql_connect($_SESSION["INI"]["DB_HOST"], $_SESSION["INI"]["DB_USER"], $_SESSION["INI"]["DB_PASSWORD"]))
      if (@mysql_select_db($_SESSION["INI"]["DB_NAME"]))
        $DB_CONN_OK = "ok";
      else $DB_CONN_ERR = mysql_error();
    else $DB_CONN_ERR = mysql_error();
    
  }
  
  $script = file_get_contents("create_tables.sql");
  preg_match_all("/CREATE TABLE `([a-z]+)`/", $script, $req_tables);
  foreach($req_tables[1] as $d) $req_tables2[$d] = true;
  //var_dump($req_tables,$req_tables2);
  if ($_POST["create_tables"]) {
    $script = preg_replace('/^\\s*--.*$/m', "", $script);
    $script = explode(";", $script);
    foreach($script as $command) mysql_query($command);
  }
  
  if ($DB_CONN_OK == "ok") {
    $r = mysql_query("SHOW TABLES");
    $DB_TAB_LIST = ""; $DB_FOUND_TABS = 0;
    while ($row = mysql_fetch_row($r)) {
      $DB_TAB_LIST.= "<li>{$row[0]}</li>\n"; if ($req_tables2[$row[0]]) $DB_FOUND_TABS++;
    }
    $DB_REQ_TABS = count($req_tables2);
    if ($DB_FOUND_TABS == $DB_REQ_TABS) { $DB_TAB_OK = "ok"; $DB_TAB_DIS = "disabled"; }
  }
  
  if (!(strlen($_SESSION['INI']['HEADER_BG_COLOR']) == 7)) $_SESSION['INI']['HEADER_BG_COLOR'] = "#202020";
  if (!(strlen($_SESSION['INI']['HEADER_SITE_TITLE']) > 1)) $_SESSION['INI']['HEADER_SITE_TITLE'] = "Noten-Verwaltung";
  
  if (!(isset($_SESSION['INI']['URL_PREFIX']))) $_SESSION['INI']['URL_PREFIX'] = URL_PREFIX;
  
  if ((strlen($_SESSION['INI']['LOGIN_SALT']) > 30) && 
      (strlen($_SESSION['INI']['LOGIN_ROOTPASW']) > 9)) {
    $CONFIG_SET_OK = "ok";
  }
  
  if ($_POST['write_config']) {
    $_SESSION['INI']['INI_DONE'] = true;
    write_config($_SESSION['INI']);
    $_SESSION['INIDONE'] = 1;
    
  }
  
  if ($_SESSION['INIDONE']) $INIDONE_OK = "ok";
  
  $doc = <<<HERE
  
  <h2>Einrichtung</h2>
  
  <p>Bitte arbeiten Sie diese Einrichtungsseite von oben nach unten ab.</p>
  
  <h3><img src='content/button_$CONFIG_PERM_OK.png'> Dateiberechtigung</h3>
  
  <p>Das Einrichtungsprogramm muss die Berechtigung haben, die Konfigurationsdatei zu erstellen bzw. zu schreiben. Dies können Sie mit folgenden Befehlen (im Hauptverzeichnis der Anwendung ausgeführt) sicherstellen:</p>
  <pre>
  touch .htconfig.php
  chmod 0666 .htconfig.php
  </pre>
  
  <h3><img src='content/button_$DB_CONN_OK.png'> Verbindung mit MySQL-Datenbank</h3>
  
  <p style='color: red'>$DB_CONN_ERR</p>
  
  <form action='install.php' method='post'>
  <table>
  <tr><td>Host:</td><td><input type='text' name='ini[DB_HOST]' value='{$_SESSION["INI"]["DB_HOST"]}' size=50></td></tr>
  <tr><td>Benutzername:</td><td><input type='text' name='ini[DB_USER]' value='{$_SESSION["INI"]["DB_USER"]}' size=50></td></tr>
  <tr><td>Passwort:</td><td><input type='text' name='ini[DB_PASSWORD]' value='{$_SESSION["INI"]["DB_PASSWORD"]}' size=50></td></tr>
  <tr><td>Datenbank-Name:</td><td><input type='text' name='ini[DB_NAME]' value='{$_SESSION["INI"]["DB_NAME"]}' size=50></td></tr>
  </table>
  
  <p><input type='submit' value=' Verbindung prüfen '></p>
  </form>
  
  <h3><img src='content/button_$DB_TAB_OK.png'> Datenbank-Tabellen einrichten</h3>
  
  <p>Vor der ersten Verwendung müssen die notwendigen Tabellen angelegt werden. Bitte verwenden Sie eine leere Datenbank, da bestehende Tabellen ohne weitere Rückfrage überschrieben werden.</p>
  
  <form action='install.php' method='post'>
  <p><input type='submit' name='create_tables' $DB_TAB_DIS value=' Tabellen erstellen '>
  </form>
  
  <p>Von $DB_REQ_TABS benötigten Tabellen wurden $DB_FOUND_TABS gefunden:</p>
  
  <ul>$DB_TAB_LIST</ul>
  
  <h3><img src='content/button_$CONFIG_SET_OK.png'> Globale Einstellungen</h3>
  
  <form action='install.php' method='post'>
  <table>
  
  <tr><td>Seitentitel:</td><td><input type='text' name='ini[HEADER_SITE_TITLE]' value='{$_SESSION["INI"]["HEADER_SITE_TITLE"]}' size=50></td></tr>
  
  <tr><td>Farbe der Kopfzeile:</td><td><input type='text' name='ini[HEADER_BG_COLOR]' value='{$_SESSION["INI"]["HEADER_BG_COLOR"]}' size=50></td></tr>
  
  <tr><td>Titelbild (z.B. Schullogo):</td><td><input type='text' name='ini[HEADER_WELCOME_IMAGE]' value='{$_SESSION["INI"]["HEADER_WELCOME_IMAGE"]}' size=50></td></tr>
  
  <tr><td>Präfix:</td><td><input type='text' name='ini[URL_PREFIX]' value='{$_SESSION["INI"]["URL_PREFIX"]}' size=50></td></tr>
  
  <tr><td>Salt-Wert:</td><td><input type='text' name='ini[LOGIN_SALT]' id='salt' value='{$_SESSION["INI"]["LOGIN_SALT"]}' readonly style='color:#bbb' size=50></td></tr>
  
  <tr><td>Passwort für Super-Administrator <code>root</code>:</td><td><input type='text' name='ini[LOGIN_ROOTPASW]' value='{$_SESSION["INI"]["LOGIN_ROOTPASW"]}' size=50></td></tr>
  
  
  
  </table>
  
  <p><input type='submit' value=' Eingabe prüfen '>
  </form>
  <script>
  function randomString(length){
     var str = "";
     for(var i = 0; i < length; ++i){
          str += String.fromCharCode(32+Math.floor(Math.random()*100));
     }
     return str;
  }
  var el = document.getElementById("salt");
  if (el.value == "") el.value = randomString(32);
  </script>
  
  <h3><img src='content/button_$INIDONE_OK.png'> Konfiguration abschließen</h3>
  
  <form action='install.php' method='post'>
  <p><input type='submit' name='write_config' value=' Konfiguration schreiben '></p>
  
  <p><input type='submit' name='logout' value=' Beenden '></p>
  </form>

  
  
HERE;
  template($doc);
  
?>