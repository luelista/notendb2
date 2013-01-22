
<h2>Dateien verwalten</h2>

<hr>
<form action="<?= URL_PREFIX ?>admin/datei/new?datei=<?= $DID ?>" method="post">
Neue Datei <input type="submit" name="create" value="Erstellen"></form>
<hr>

<table width=400>
<tr><th>Jahr</th><th>Hj.</th><th>Schulform</th><th>Stufe</th><th>Aktion</th></tr>
<?php foreach($Dateien as $d): if ($d["archiviert"]) continue; ?>
<tr>
<td><?= $d["jahr"] ?></td>
<td><?= $d["hj"] ?></td>
<td><?= $d["schulform"] ?></td>
<td><?= $d["stufe"] ?></td>
<td><a href="<?= URL_PREFIX ?>admin/datei/<?= $d["did"] ?>?datei=<?= $DID ?>">Bearbeiten</a></td>
</tr>
<?php endforeach; ?>
</table>
<hr>
Archivierte Dateien:
<hr>

<table width=400>
<tr><th>Jahr</th><th>Hj.</th><th>Schulform</th><th>Stufe</th><th>Aktion</th></tr>
<?php foreach($Dateien as $d): if (!$d["archiviert"]) continue; ?>
<tr>
<td><?= $d["jahr"] ?></td>
<td><?= $d["hj"] ?></td>
<td><?= $d["schulform"] ?></td>
<td><?= $d["stufe"] ?></td>
<td><a href="<?= URL_PREFIX ?>admin/datei/<?= $d["did"] ?>?datei=<?= $DID ?>">Bearbeiten</a></td>
</tr>
<?php endforeach; ?>
</table>

