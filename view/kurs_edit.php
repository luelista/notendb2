
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
  
  <h3>Allgemein</h3>
  
  <br>
  <b>Hier eine Vorlage auswählen...</b><br>
  <select id="selKursTemplate" style="width:300px">
    <option value="">- Hier klicken, um Vorlage zu kopieren -</option>
    <?php foreach($KursTemplates as $d): ?>
    <option value="<?=$d["ktid"]?>"><?=$d["art"]?> <?=$d["name"]?> - <?=$d["thema"]?></option>
    <?php endforeach; ?>
  </select>
  <br><br><br>
  
  ...oder Daten von Hand eingeben:
  <table>
    
    <tr><td>Name: </td><td><input type="input" name="e[name]" value="<?= htmlspecialchars($Data["name"]) ?>"></td></tr>
    <tr><td>Gewichtung: </td><td><input type="input" name="e[gewichtung]" value="<?= htmlspecialchars($Data["gewichtung"]) ?>"></td></tr>
    <tr><td>Art (LK/GK): </td><td><input type="input" name="e[art]" value="<?= htmlspecialchars($Data["art"]) ?>"></td></tr>
    <tr><td>Wochenstunden: </td><td><input type="input" name="e[wochenstunden]" value="<?= htmlspecialchars($Data["wochenstunden"]) ?>"></td></tr>
    <tr><td>Thema: </td><td><input type="input" name="e[thema]" value="<?= htmlspecialchars($Data["thema"]) ?>"></td></tr>
    <tr><td><br></td><td></td></tr>
    
    <tr><td>Anzeigeposition: </td><td><input type="input" name="e[display_position]" value="<?= htmlspecialchars($Data["display_position"]) ?>"></td></tr>
    <tr><td>Exportposition: </td><td><input type="input" name="e[export_position]" value="<?= htmlspecialchars($Data["export_position"]) ?>"></td></tr>
    <tr><td>Fachrichtung: </td><td><input type="input" name="e[fachrichtung]" value="<?= htmlspecialchars($Data["fachrichtung"]) ?>"></td></tr>
    
  </table>
  
  <input type="submit" value="   Speichern   " style="background:#7f7">
  <!-- input type="submit" value="   Speichern und Neu  " title="Diesen Eintrag erstellen und danach einen weiteren Eintrag erstellen"-->
  
  
  </td><td valign=top>
  
  <h3>Zugeordnete Lehrer</h3>
  
  <select name="rlk_list[]" size=12 multiple>
  <?php foreach($Lehrer as $d): ?>
  <option value="<?= $d["lid"] ?>" <?= $d["r_kuid"] ? "selected" : "" ?>><?= $d["name"] ?>, <?= $d["vorname"] ?></option>
  <?php endforeach; ?>
  
  </select>
  <br>
  Um mehrere Lehrer auszuwählen "Strg" auf der Tastatur gedrückt halten.
  </td><td valign=top>
  
  <h3>Zugeordnete&nbsp;Schüler</h3>
  <ul>
  <?php foreach($Schueler as $d): ?>
  <li><?= $d["name"] ?>, <?= $d["vorname"] ?></li>
  <?php endforeach; ?>
  </ul>
  
  </td></tr></table>
  
  </form>
  
  <?php if($Kuid): ?>
  <form action="<?=URL_PREFIX?>kurs/delete?datei=<?= $DID ?>" method="post">
  <input type="hidden" name="kuid" value="<?= $Kuid ?>">
  <input type="submit" name="delete" value="Kurs löschen" style="background:#fcc;position:absolute;margin:-25px 0 0 200px">
  
  </form>
  <?php endif; ?>