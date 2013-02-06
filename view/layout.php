<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?= $DocTitle ?> - <?= $SiteTitle ?></title>
<link rel="stylesheet" type="text/css" href="<?=URL_PREFIX?>content/style.css">
<link rel='shortcut icon' href='http://mw.wikilab.de/favicon.png'/>
<script src="<?= URL_PREFIX ?>content/jquery.js"></script>
<script>
  window.ScriptInfo = <?= json_encode($ScriptInfo) ?>;
</script>
<style>
#header {
  background: <?= HEADER_BG_COLOR?>;
  background-image: linear-gradient(bottom, <?= HEADER_BG_COLOR?> 0%, <?= HEADER_BG_COLOR_2?> 100%);
  background-image: -o-linear-gradient(bottom, <?= HEADER_BG_COLOR?> 0%, <?= HEADER_BG_COLOR_2?> 100%);
  background-image: -moz-linear-gradient(bottom, <?= HEADER_BG_COLOR?> 0%, <?= HEADER_BG_COLOR_2?> 100%);
  background-image: -webkit-linear-gradient(bottom, <?= HEADER_BG_COLOR?> 0%, <?= HEADER_BG_COLOR_2?> 100%);
  background-image: -ms-linear-gradient(bottom, <?= HEADER_BG_COLOR?> 0%, <?= HEADER_BG_COLOR_2?> 100%);
  background-image: -webkit-gradient(linear,left bottom,left top,color-stop(0, <?= HEADER_BG_COLOR_1?>),color-stop(1, <?= HEADER_BG_COLOR_2?>));
}
</style>
</head>
<body>

<div id="header">
<div class="wrapper">
  <div class="sysmenu">
    <strong><?= $Benutzername ?></strong>
    
    | <a href="<?=URL_PREFIX?>user/change_password?datei=<?=$DID?>">Passwort ändern</a>
    | <a href="<?=URL_PREFIX?>user/logout">Abmelden</a>
    
    <br><small>
    <?php if ($IsAdmin): ?>
      <a href="<?=URL_PREFIX?>admin/dashboard">Administrationsbereich</a>
    <?php endif; ?>
    <?php if ($IsAdmin||$IsTutor): ?>
    - <a href="<?=URL_PREFIX?>kurs_template/view?datei=<?=$DID?>">Kursvorlagen</a>
    <?php endif; ?>
    </small>
  </div>
  <h1>
    <a href="<?=URL_PREFIX?>">Noten-Verwaltung</a>
  </h1>
</div>
</div>

<div id="nav">
<div class="wrapper">
  <div class="sel">
    <?php if(isset($Dateien)): ?>
    Datei:
    <select onchange="location='?datei='+this.value">
    <option value="-1">Auswahl</option>
    <optgroup label="Aktuell">
    <?php foreach($Dateien as $d): if ($d["archiviert"]) continue; ?>
    <option value=<?= $d["did"] . ($d["did"] == $DID ? " selected" : "") ?>><?= $d["descr"] ?><?=(($d["tutor"])?"[".$d["tutor"]."]":"")?></option>
    <?php endforeach; ?>
    </optgroup>
    <optgroup label="Archiviert">
    <?php foreach($Dateien as $d): if (!$d["archiviert"]) continue; ?>
    <option value=<?= $d["did"] . ($d["did"] == $DID ? " selected" : "") ?>><?= $d["descr"] ?><?=(($d["tutor"])?"[".$d["tutor"]."]":"")?></option>
    <?php endforeach; ?>
    </optgroup>
    </select>
    <?php endif; ?>
  </div>
  <ul>
    <?php foreach($Main_Menu as $k=>$v): ?>
    <li>
      <a href="<?= URL_PREFIX . $k ?>?datei=<?=$DID?>"<?= $k == "$controller_class/$controller_function" ? " class='current'" : "" ?>>
      <?= $v ?></a>
    </li>
    <?php endforeach; ?>
  </ul>
  <div class="clear"></div>
</div>
</div>

<div id="content">
<div class="wrapper">
  <?= $Inhalt ?>
  
  
</div>
</div>

<div id="footer">
<div class="wrapper">
<b style="float:right;color:black;">Bei technischen Fragen und Problemen können Sie uns per Mail an <a href="mailto:notendb@wikilab.de">notendb@wikilab.de</a> erreichen.</b>
  (c) 2012 Max Weller, Moritz Willig
</div>
</div>

<!-- Copyright (c) 2012 "MW" Max Weller, Moritz Willig, mw.wikilab.de -->

</body>
</html>
