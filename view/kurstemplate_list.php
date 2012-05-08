
<form action="<?= URL_PREFIX ?>kurstemplate/edit/new?datei=<?= $DID ?>" method="post" style="float:right">
<input type="submit" name="create" value="Neue Kursvorlage erstellen"></form>

<h2>Liste der Kursvorlagen</h2>


<table width=100%>
<tr><th>Art</th><th>Wst.</th><th>Name</th><th>Thema</th><th width=170>Aktion</th></tr>
<?php foreach($Liste as $d): ?>
<tr>
<td><?= $d["art"] ?></td>
<td><?= $d["wochenstunden"] ?></td>
<td><?= $d["name"] ?></td>
<td><?= $d["thema"] ?></td>
<td>
  <input onclick="location='<?= URL_PREFIX ?>kurstemplate/edit/<?= $d["ktid"] ?>?datei=<?= $DID ?>'" type="button" value="Bearbeiten"
  ><form action="<?= URL_PREFIX ?>kurstemplate/delete?datei=<?= $DID ?>" method="post" style="display:inline;padding:0;margin:0;"
  ><input type="hidden" name="ktid" value="<?= $d["ktid"]?>"
  ><input type="submit" name="delete" value="Löschen">
  </form>
</td>
</tr>
<?php endforeach; ?>
</table>

