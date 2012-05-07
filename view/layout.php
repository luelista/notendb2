<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>db</title>
<link rel="stylesheet" type="text/css" href="<?=URL_PREFIX?>view/style.css">
<link rel='shortcut icon' href='http://mw.wikilab.de/favicon.png'/>
</head>
<body>

<div id="header">
<div class="wrapper">
  <div class="sysmenu">
    <strong><?= $Benutzername ?></strong>
    <?php if ($IsAdmin): ?>
    | <a href="<?=URL_PREFIX?>admin/lehrer_list?datei=<?=$DID?>">Lehrer</a>
    | <a href="<?=URL_PREFIX?>admin/dateien?datei=<?=$DID?>">Dateien</a>
    <?php endif; ?>
    | <a href="<?=URL_PREFIX?>user/change_password?datei=<?=$DID?>">Passwort ändern</a>
    | <a href="<?=URL_PREFIX?>user/logout">Abmelden</a>
  </div>
  <h1>
    <a href="<?=URL_PREFIX?>">Noten-Verwaltung</a>
  </h1>
</div>
</div>

<div id="nav">
<div class="wrapper">
  <div style="float:right">
    <?php if(isset($Dateien)): ?>
    Datei:
    <select onchange="location='?datei='+this.value">
    <option value="-1">Auswahl</option>
    <?php foreach($Dateien as $d): ?>
    <option value=<?= $d["did"] . ($d["did"] == $DID ? " selected" : "") ?>><?= $d["descr"] ?></option>
    <?php endforeach; ?>
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
  <div style="clear: both"></div>
</div>
</div>

<div id="content">
<div class="wrapper">
  <?= $Inhalt ?>
  
  
</div>
</div>

<div id="footer">
<div class="wrapper">
  Copyright (c) 2012 Max Weller, Moritz Willig
</div>
</div>

<!-- Copyright (c) 2012 MW, mw.wikilab.de -->

</body>
</html>
