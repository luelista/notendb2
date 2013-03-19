
<div style="float:right;width:256px">
  <br><br>
  
  <a href="http://notendb-hilfe.wikilab.de/anleitungen/" style="text-decoration: none; color: blue; font-weight: bold;">
  <img src="<?= URL_PREFIX ?>content/Help.png" style="vertical-align:middle">
  Hilfe und Anleitung</a>
  <br><br>
  <img src="http://www.wvss-wetzlar.de/moodle/theme/fandango/pix/wuerfel.gif" style="width:256px">

</div>


<h1>Willkommen</h1>

<p> Bitte wählen Sie zuerst eine Datei aus der Liste unten bzw. aus dem Drop-Down-Feld rechts oben aus.
Klicken Sie anschließend auf einen Menüpunkt.</p>
<br>
<h3><img src="<?= URL_PREFIX ?>content/icons/document-open.png"> Datei auswählen ...</h3>

<table width=400>
<tr><th colspan=2>Schulform-Stufe</th><th>Jahr</th><th>Hj.</th><th></th><th>Aktion</th></tr>
<?php foreach($Dateien as $d): if ($d["archiviert"]) continue; ?>
<tr>
  <td><img src="<?= URL_PREFIX ?>content/icons/x-office-spreadsheet.png"> </td>
<td><?= $d["schulform"] ?> <?= $d["stufe"] ?></td>
<td><?= $d["jahr"] ?></td>
<td><?= $d["hj"] ?></td>
<td><?= $d["tutor"] ? "<b style=color:green>TUTOR</b>" : "" ?></td>
<td><a href="<?= URL_PREFIX ?>kurs/view?datei=<?= $d["did"] ?>">Datei öffnen</a></td>
</tr>
<?php endforeach; ?>
</table>

<br>
<h3><img src="<?= URL_PREFIX ?>content/icons/package-x-generic.png"> Archivierte Dateien <a href="javascript://" onclick="$('#archivedfiles').show();$(this).hide();">anzeigen</a></h3>

<table width=400 id="archivedfiles" style="display:none">
<tr><th colspan=2>Schulform-Stufe</th><th>Jahr</th><th>Hj.</th><th></th><th>Aktion</th></tr>
<?php foreach($Dateien as $d): if (!$d["archiviert"]) continue; ?>
<tr><td><img src="<?= URL_PREFIX ?>content/icons/x-office-spreadsheet.png"> </td>
<td><?= $d["schulform"] ?> <?= $d["stufe"] ?></td>
<td><?= $d["jahr"] ?></td>
<td><?= $d["hj"] ?></td>
<td><?= $d["tutor"] ? "<b style=color:green>TUTOR</b>" : "" ?></td>
<td><a href="<?= URL_PREFIX ?>kurs/view?datei=<?= $d["did"] ?>">Datei öffnen</a></td>
</tr>
<?php endforeach; ?>
</table>



<br style="clear:right">
