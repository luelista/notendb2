
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
  });
  
  var dateien={
  <?php 
  /*
    //var_dump($Dateien);
    foreach($Dateien as $d) {
      echo $d["did"].":"."[".
        "jahr:"
        "hj:"
        "schulform:"
        "stufe:"
        "archv:"
        "kurse:":$d["kurse"]
      ."]"
    }
    */
  ?>
  };
  
  function adjustKurs() {
    $val=$("#selDateiCopy").val();
    
    if (!dateien[val]) {
      $("#selKursCopy").attr('disabled', 'disabled');
    } else {
      $("#selKursCopy").removeAttr('disabled');
    }
    
  }
</script>
<style>
tt { background-color: #f2e600 }
tr.internal { color: #bbb; }
tr.internal input { color: #aaa; background: #eee; }
h3 { margin-bottom: 5px; }
</style>

  <?php if($Error): ?>
  <h2>Fehler</h2>
  
  <p><?= $Error ?></p>
  <?php endif; ?>
  
  <form action="<?=URL_PREFIX?><?= $MethodURL ?>asd?datei=<?= $DID ?>" method="post">
  <h3><tt>1.</tt> Kurs und Schüler aus Datei kopieren:</h3>
  
  <table>
    <tr><td>
    Datei:</td><td><select id="selDateiCopy" style="width:300px" onchange="adjustKurs()">
      <option value="">- Hier klicken zum Auswählen -</option>
      <?php foreach($Dateien as $d): ?>
      <option value="<?=$d["did"]?>"><?=$d["art"]?> <?=$d["name"]?> - <?=$d["thema"]?></option>
      <?php endforeach; ?>
    </select>
    </td></tr>
    <tr><td>
    Kurs:</td><td><select id="selKursCopy" style="width:300px" disabled>
      <option value="">- Hier klicken zum Auswählen -</option>
      <?php foreach($KursTemplates as $d): ?>
      <option value="<?=$d["ktid"]?>"><?=$d["art"]?> <?=$d["name"]?> - <?=$d["thema"]?></option>
      <?php endforeach; ?>
    </select>
    </td></tr>
  </table>
  <input type="submit" value="   Kopieren   " style="background:#7f7">
  
  </form>
  <hr>
  <b>oder</b>
  <hr>
  
  <form action="<?=URL_PREFIX?><?= $MethodURL ?>?datei=<?= $DID ?>" method="post">
  
  <table width=100%><tr><td valign=top style="padding-right: 20px">
  
  <h3><tt>1.</tt> Kurs / Thema auswählen:</h3>
  <select id="selKursTemplate" style="width:300px">
    <option value="">- Hier klicken zum Auswählen -</option>
    <?php foreach($KursTemplates as $d): ?>
    <option value="<?=$d["ktid"]?>"><?=$d["art"]?> <?=$d["name"]?> - <?=$d["thema"]?></option>
    <?php endforeach; ?>
  </select>
  <br><br><br>
  
  <h3><tt>2.</tt> Informationen überprüfen:</h3>
  
  <small>Bei Unstimmigkeiten bitte Tutor/Admin kontaktieren.</small>
  
  <br>

  <table width=100%>
    
    <tr><td>Fach: </td><td><input type="input" name="e[name]" value="<?= htmlspecialchars($Data["name"]) ?>" style="width:100%"></td></tr>
    
    <tr><td colspan=2>Thema: </td></tr><tr><td colspan=2><input type="input" name="e[thema]" value="<?= htmlspecialchars($Data["thema"]) ?>" style="width:100%"></td></tr>
    <tr><td>Art (LK/GK): </td><td><input type="input" name="e[art]" value="<?= htmlspecialchars($Data["art"]) ?>" style="width:100%"></td></tr>
    <tr><td>Wochen-<br>stunden: </td><td><input type="input" name="e[wochenstunden]" value="<?= htmlspecialchars($Data["wochenstunden"]) ?>" style="width:100%"></td></tr>
    <tr><td colspan=2><br>
      <input type="button" onclick="if(confirm('Möchten Sie diesen Kurs einschließlich der zugeordneten Informationen löschen?')) document.forms.deleteform.submit()" value="Kurs löschen" style="background:#fcc; float:right;">
  <input type="submit" value="   Speichern   " style="background:#7f7">
  <!-- input type="submit" value="   Speichern und Neu  " title="Diesen Eintrag erstellen und danach einen weiteren Eintrag erstellen"-->
  
<br><br>
    </td></tr>
    
    <tr class="internal"><td>Anzeigepos.: </td><td><input type="input" name="e[display_position]" value="<?= htmlspecialchars($Data["display_position"]) ?>"></td></tr>
    <tr class="internal"><td>Exportpos.: </td><td><input type="input" name="e[export_position]" value="<?= htmlspecialchars($Data["export_position"]) ?>"></td></tr>
    <tr class="internal"><td>Fachrichtung: </td><td><input type="input" name="e[fachrichtung]" value="<?= htmlspecialchars($Data["fachrichtung"]) ?>"></td></tr>
    <tr class="internal"><td>Gewichtung: </td><td><input type="input" name="e[gewichtung]" value="<?= htmlspecialchars($Data["gewichtung"]) ?>"></td></tr>
  </table>  
  </td><td valign=top>
  
  <h3><tt>3.</tt> Kurslehrer wählen</h3>
  
  <select name="rlk_list[]" size=12 multiple>
  <?php foreach($Lehrer as $d): ?>
  <option value="<?= $d["lid"] ?>" <?= $d["r_kuid"] ? "selected" : "" ?>><?= $d["name"] ?>, <?= $d["vorname"] ?></option>
  <?php endforeach; ?>
  
  </select>
  <br>
  <small>Tipp: Um mehrere Lehrer auszuwählen "Strg" auf der Tastatur gedrückt halten.</small>
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
  <form action="<?=URL_PREFIX?>kurs/delete?datei=<?= $DID ?>" method="post" name="deleteform">
  <input type="hidden" name="kuid" value="<?= $Kuid ?>"><input type="hidden" name="delete" value="Kurs löschen">
  
  
  </form>
  <?php endif; ?>