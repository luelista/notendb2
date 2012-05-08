
<script>
  $(function() {
    $("#selKursTemplate").change(function() {
      var id = $("#selKursTemplate").val();
      if (id) {
        var $note = $("<p>Bitte warten...</p>").insertAfter("#selKursTemplate").css({background:"#fea",padding:"100px",position:"absolute"});
        $.get("<?= URL_PREFIX ?>kurs_template/loadajax/" + id, function(data) {
          for(var k in data.template) {
            $("input[name='e["+k+"]']").val(data.template[k]);
          }
          $note.remove();
        }, "json");
      }
    });
  })
</script>

  <?php if($Error): ?>
  <h2>Fehler</h2>
  
  <p><?= $Error ?></p>
  <?php endif; ?>
  
  <form action="<?=URL_PREFIX?><?= $MethodURL ?>?datei=<?= $DID ?>" method="post">
  
  <table width=100%><tr><td valign=top>
  
  <h4>Allgemein</h4>
  
  
  Vorlage kopieren:
  <select id="selKursTemplate" style="width:300px">
    <option value="">- Hier klicken, um Vorlage zu kopieren -</option>
    <?php foreach($KursTemplates as $d): ?>
    <option value="<?=$d["ktid"]?>"><?=$d["art"]?> <?=$d["name"]?> - <?=$d["thema"]?></option>
    <?php endforeach; ?>
  </select>
  <br><br>
  
  <table>
  <?php foreach($Data as $k=>$v): ?>
  <tr><td><?= ucfirst($k) ?>: </td><td><input type="input" name="e[<?= $k ?>]" value="<?= htmlspecialchars($v) ?>"></td></tr>
  <?php endforeach; ?>
  </table>
  
  <input type="submit" value="   Speichern   ">
  <input type="submit" value="   Speichern und Neu  " title="Diesen Eintrag erstellen und danach einen weiteren Eintrag erstellen">
  
  
  </td><td valign=top>
  
  <h4>Zugeordnete Lehrer</h4>
  
  <select name="rlk_list[]" size=12 multiple>
  <?php foreach($Lehrer as $d): ?>
  <option value="<?= $d["lid"] ?>" <?= $d["r_kuid"] ? "selected" : "" ?>><?= $d["name"] ?>, <?= $d["vorname"] ?></option>
  <?php endforeach; ?>
  
  </select>
  
  </td><td valign=top>
  
  <h4>Zugeordnete&nbsp;Schüler</h4>
  
  <?php foreach($Schueler as $d): ?>
  <li><?= $d["name"] ?>, <?= $d["vorname"] ?></li>
  <?php endforeach; ?>
  
  
  </td></tr></table>
  
  </form>
  
  <?php if($Kuid): ?>
  <form action="<?=URL_PREFIX?>kurs/delete?datei=<?= $DID ?>" method="post">
  <input type="hidden" name="kuid" value="<?= $Kuid ?>">
  <input type="submit" name="delete" value="Kurs löschen">
  
  </form>
  <?php endif; ?>