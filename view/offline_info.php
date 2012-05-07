
<h2>Export und Import für Offline-Verwendung</h2>

<h3>Export</h3>
<form action="<?= URL_PREFIX ?>offline/export/<?= $DID ?>" method="post">

<?php if($isTutor): ?>

<p>[TODO: HelpText Tutor] Wähle aus für welchen Lehrer die aktuell ausgewählte Datei exportiert werden soll.
Klicke anschließend auf den folgenden Button.</p>

<p>Lehrer:

<select name="lehrerId">
<option value="">Alle Lehrer</option>
<?php foreach($Lehrer as $d): ?>
<option value="<?= $d["lid"] ?>">(<?= $d["kuerzel"] ?>) <?= $d["anrede"] ?> <?= $d["vorname"] ?> <?= $d["name"] ?></option>
<?php endforeach; ?>
</select>

<?php else: ?>

<p>[TODO: HelpText NICHT Tutor] Klicke auf den folgenden Button, um die aktuelle Datei zu exportieren.</p>

<input type="hidden" name="lehrerId" value="<?= $lehrerId ?>" />

<?php endif; ?>
<p><input type="submit" value="   Exportieren   "></p>

</form>


<hr>
<h3>Import</h3>

<form action="<?= URL_PREFIX ?>offline/import/?datei=<?= $DID ?>" method="post" enctype="multipart/form-data">


<p>
	[TODO: HelpText Import] Klicke das folgende Feld an und wähle eine Datei aus, 
	die mit der Offline-Noten-Datenbank erstellt wurde und die jetzt importiert werden soll.
</p>

<p><input type="file" name="importFile"></p>

<p><input type="submit" name="doImport" value="   Weiter ...    " /></p>

</form>

