
<img src="http://www.wvss-wetzlar.de/moodle/theme/fandango/pix/wuerfel.gif" style="float:right;width:256px">

<h1>Willkommen</h1>

<p> Bitte wählen Sie zuerst eine Datei aus der Liste unten bzw. aus dem Drop-Down-Feld rechts oben aus.
Klicken Sie anschließend auf einen Menüpunkt.</p>

<h3>Datei auswählen ...</h3>

<table width=400>
<tr><th>Jahr</th><th>Hj.</th><th>Schulform</th><th>Stufe</th><th></th><th>Aktion</th></tr>
<?php foreach($Dateien as $d): ?>
<tr>
<td><?= $d["jahr"] ?></td>
<td><?= $d["hj"] ?></td>
<td><?= $d["schulform"] ?></td>
<td><?= $d["stufe"] ?></td>
<td><?= $d["tutor"] ? "<b style=color:green>TUTOR</b>" : "" ?></td>
<td><a href="<?= URL_PREFIX ?>kurs/view?datei=<?= $d["did"] ?>">Datei öffnen</a></td>
</tr>
<?php endforeach; ?>
</table>



<br style="clear:right">
