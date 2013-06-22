
<form action="<?= URL_PREFIX ?>kurstemplate/edit/new?datei=<?= $DID ?>" method="post" style="float:right">
<input type="submit" name="create" value="Neue Kursvorlage erstellen"></form>

<h2>Liste der Kursvorlagen</h2>


<table width=100%>
<tr>
<th>Art</th><th>Wst.</th><th>Name</th><th>Thema</th><th width=170>Aktion</th></tr>
<?php foreach($Liste as $d): $group = "$d[schulform]$d[stufe] - $d[hj]. Hj."; if ($group != $lastgroup) { echo "<tr><th colspan=5>$group</th></tr>"; $lastgroup=$group; } ?>
<tr onclick="showIframe(this, '<?= URL_PREFIX ?>kurstemplate/edit/<?= $d["ktid"] ?>?datei=<?= $DID ?>');">
<td><?= $d["art"] ?></td>
<td><?= $d["wochenstunden"] ?></td>
<td><?= $d["name"] ?></td>
<td><?= $d["thema"] ?></td>
<td>
  <input class="editBtn" type="button" value="Bearbeiten"
  ><form action="<?= URL_PREFIX ?>kurstemplate/delete?datei=<?= $DID ?>" method="post" onsubmit="return confirm('Sind Sie sicher?');" style="display:inline;padding:0;margin:0;"
  ><input type="hidden" name="ktid" value="<?= $d["ktid"]?>"
  ><input type="submit" name="delete" value="Löschen">
  </form>
</td>
</tr>
<?php endforeach; ?>
</table>

<script>
  function showIframe(tr, url) {
    if ($("#editFrameRow").size()){ $("#editFrameRow").remove(); $(".editBtn").val("Bearbeiten");return; }
    
    $(tr).find(".editBtn").val("Schließen");
    $("<tr id='editFrameRow'><td colspan=6><iframe src='' style='width:100%;height:450px;background:#fea;'></iframe></td></tr>")
    .insertAfter(tr).find('iframe').attr('src',url);
  }
  
  
</script>

