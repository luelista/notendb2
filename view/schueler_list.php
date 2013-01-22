
<h2>Liste der Schüler in dieser Datei</h2>

<?php if($archiv): ?>
<div style='font:status-bar;color:red'>Archivierte Datei - bearbeiten nicht möglich!</div>
<?php else: ?>
  <form action="<?= URL_PREFIX ?>schueler/edit/new?datei=<?= $DID ?>" method="post">
  <input type="submit" name="create" value="Erstellen"></form>
  
  <?php if ($isTutor) { ?>
  <form action="<?= URL_PREFIX ?>schueler/import?datei=<?= $DID ?>" enctype="multipart/form-data" method="post">
  <input type="file" name="file"></input>
  <input type="submit" name="create" value="Importieren"></form>
  <?php } ?>
<?php endif; ?>
<br>


<table width=700>
<tr><th>Name</th><th>Vorname</th><th>Geburtsdatum</th><th>Aktion</th></tr>
<?php foreach($Liste as $d): ?>
<tr>
<td><?= $d["name"] ?></td>
<td><?= $d["vorname"] ?></td>
<td><?= $d["geburtsdatum"] ?></td>
<td><?php if(!$archiv): ?><a href="<?= URL_PREFIX ?>schueler/edit/<?= $d["sid"] ?>?datei=<?= $DID ?>">Bearbeiten</a><?php endif; ?></td>
</tr>
<?php endforeach; ?>
</table>

