<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>db</title>
<link rel="stylesheet" type="text/css" href="<?=URL_PREFIX?>content/style.css">
<link rel='shortcut icon' href='http://mw.wikilab.de/favicon.png'/>
<script src="<?= URL_PREFIX ?>content/jquery.js"></script>
</head>
<body>

<div id="header">
<div class="wrapper">
  <div class="sysmenu">
    <strong><?= $Benutzername ?></strong>
    | <a href="<?=URL_PREFIX?>kurs_template/view?datei=<?=$DID?>">Kursvorlagen</a>
    | <a href="<?=URL_PREFIX?>user/change_password?datei=<?=$DID?>">Passwort ändern</a>
    | <a href="<?=URL_PREFIX?>user/logout">Abmelden</a>
    <?php if ($IsAdmin): ?>
    <br><small>
      Administration:
      <a href="<?=URL_PREFIX?>admin/lehrer_list?datei=<?=$DID?>">Lehrer</a>
    | <a href="<?=URL_PREFIX?>admin/dateien?datei=<?=$DID?>">Dateien</a>
    </small>
    <?php endif; ?>
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
    <?php foreach($Dateien as $d): ?>
    <option value=<?= $d["did"] . ($d["did"] == $DID ? " selected" : "") ?>><?= $d["descr"] ?><?=(($d["tutor"])?"[".$d["tutor"]."]":"")?></option>
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
  (c) 2012 Max Weller, Moritz Willig
</div>
</div>

<!-- Copyright (c) 2012 "MW" Max Weller, Moritz Willig, mw.wikilab.de -->

</body>
</html>
