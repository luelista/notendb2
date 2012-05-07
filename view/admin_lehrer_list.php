
<form action="<?= URL_PREFIX ?>admin/lehrer/new?datei=<?= $DID ?>" method="post" style="float:right;margin-top:10px">
<input type="submit" name="create" value="Erstellen"></form>

<h3>Lehrerliste</h3>

<hr>

<table width=700>
<tr><th>Kürzel</th><th>Name</th><th>Aktion</th></tr>
<?php foreach($Lehrer as $d): ?>
<tr>
<td><?= $d["kuerzel"] ?></td>
<td><?= $d["anrede"] ?> <?= $d["vorname"] ?> <?= $d["name"] ?></td>
<td><a href="<?= URL_PREFIX ?>admin/lehrer/<?= $d["lid"] ?>?datei=<?= $DID ?>">Bearbeiten</a></td>
<td><a href="<?= URL_PREFIX ?>admin/set_password/<?= $d["lid"] ?>?datei=<?= $DID ?>">Passwort setzen</a></td>
</tr>
<?php endforeach; ?>
</table>

