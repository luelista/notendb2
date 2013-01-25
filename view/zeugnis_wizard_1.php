
<div style="float:right;width:160px;padding-left: 10px; border-left: 1px solid #bbb;">
  Download der Vorlagen:<br>
  <a href="<?= URL_PREFIX ?>content/Fertige%20Vorlagen/BG12_Vorlage.doc">BG12_Vorlage.doc</a>
  <a href="<?= URL_PREFIX ?>content/Fertige%20Vorlagen/BG13_Vorlage.doc">BG13_Vorlage.doc</a>
  <br><br>
  Sonderfunktionen:<br>
  
  <a href="<?= URL_PREFIX ?>statistics/latinum?datei=<?= $DID ?>">Schüler mit Latinum exportieren  </a>
  
</div>

<h2>Zeugnisdruck - Schritt 1: Tutorengruppe wählen</h2>


<form action="<?= URL_PREFIX ?>tabelle/zeugnis_2?datei=<?= $DID ?>" method="post">

<select name="kuid">
<optgroup label="Eigene Kurse">
<?php foreach($Kurse as $d): if ($d["lehrer_namen"]!=$curLehrerNachname) continue; ?>
<option value="<?= $d["kuid"] ?>"><?= $d["art"] ?> <?= $d["name"] ?> (<?= $d["lehrer_namen"] ?>)</option>
<?php endforeach; ?>
</optgroup>
<optgroup label="Alle Leistungskurse">
<?php foreach($Kurse as $d): if ($d["art"] != "LK") continue; ?>
<option value="<?= $d["kuid"] ?>"><?= $d["name"] ?> (<?= $d["lehrer_namen"] ?>)</option>
<?php endforeach; ?>
</optgroup>
<optgroup label="Alle Grundkurse und andere">
<?php foreach($Kurse as $d): if ($d["art"] == "LK") continue; ?>
<option value="<?= $d["kuid"] ?>"><?= $d["name"] ?> (<?= $d["lehrer_namen"] ?>)</option>
<?php endforeach; ?>
</optgroup>
</select>


<h2>Zeugnisdruck - Schritt 2: Felderreihenfolge definieren</h2>


<table>

<tr>
<th>KursID</th>
<th>LK/GK</th>
<th>WoStd.</th>
<th>Fach</th>
<th>Position</th>
</tr>

<?php foreach($Kurse as $d): ?>
<tr>
<td><?= $d["kuid"] ?></td>
<td><?= $d["art"] ?></td>
<td><?= $d["wochenstunden"] ?></td>
<td><?= $d["name"] ?> (<?= $d["lehrer_namen"] ?>)</td>
<td><input type="text" name="export_position[<?= $d["kuid"] ?>]" value="<?= $d["export_position"] ?>"></td>
</tr>
<?php endforeach; ?>

</table>



<br><br>
<input type="submit" name="create" value="   Weiter >>   ">


</form>

