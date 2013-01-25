
<h2>Liste der Kurse in dieser Datei</h2>

<?php if($archiv): ?>
<div style='font:status-bar;color:red'>Archivierte Datei - bearbeiten nicht möglich!</div>
<?php else: ?>
<form action="<?= URL_PREFIX ?>kurs/edit/new?datei=<?= $DID ?>" method="post">
<input type="submit" name="create" value="Kurs erstellen"></form>
<br>
<?php endif; ?>

<table width=700>
<tr><th>Mein<br>Kurs?</th><th>Art</th><th>Wst.</th><th>Name</th><th>Zugeordnete Lehrer</th><th>Aktion</th></tr>
<?php foreach($Liste as $d): ?>
<tr>
<td>
<img src="<?= URL_PREFIX . "content/button_" . ($d["rlk_set"] ? "ok" : "cancel") . ".png" ?>" /></td>
<td><?= $d["art"] ?></td>
<td><?= $d["wochenstunden"] ?></td>
<td><?= $d["name"] ?></td>
<td><?= $d["lehrer_namen"] ?></td>
<td><?php if (!$archiv): ?><a href="<?= URL_PREFIX ?>kurs/edit/<?= $d["kuid"] ?>?datei=<?= $DID ?>">Bearbeiten</a><?php endif; ?></td>
</tr>
<?php endforeach; ?>
</table>

