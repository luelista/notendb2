
<h2>Zeugnisdruck - Schritt 1: Tutorengruppe wählen</h2>


<form action="<?= URL_PREFIX ?>tabelle/zeugnis_2?datei=<?= $DID ?>" method="post">

<select name="kuid">
<?php foreach($Kurse as $d): ?>
<option value="<?= $d["kuid"] ?>"><?= $d["name"] ?> (<?= $d["lehrer_namen"] ?>)</option>
<?php endforeach; ?>
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

