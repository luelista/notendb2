
<h2>Liste der Kurse in dieser Datei</h2>

<form action="<?= URL_PREFIX ?>kurs/edit/new?datei=<?= $DID ?>" method="post">
<input type="submit" name="create" value="Erstellen"></form>

<table width=700>
<tr><th>Mein Kurs?</th><th>Art</th><th>Wst.</th><th>Name</th><th>Zugeordnete Lehrer</th><th>Aktion</th></tr>
<?php foreach($Liste as $d): ?>
<tr>
<td><input type="checkbox" name="rlk_set[<?= $d["kuid"] ?>]" value="1" <?= $d["rlk_set"] ? "checked" : "" ?>></td>
<td><?= $d["art"] ?></td>
<td><?= $d["wochenstunden"] ?></td>
<td><?= $d["name"] ?></td>
<td><?= $d["lehrer_namen"] ?></td>
<td><a href="<?= URL_PREFIX ?>kurs/edit/<?= $d["kuid"] ?>?datei=<?= $DID ?>">Bearbeiten</a></td>
</tr>
<?php endforeach; ?>
</table>

