
  <?php if($Error): ?>
  <h2>Fehler</h2>
  
  <p><?= $Error ?></p>
  <?php endif; ?>
  
  <form action="<?=URL_PREFIX?><?= $MethodURL ?>?datei=<?= $DID ?>" method="post">
  
  <table width=100%><tr><td valign=top>
  
  <h4>Allgemeine Informationen</h4>
  
  <table>
  <?php foreach($Data as $k=>$v): ?>
  <tr><td><?= ucfirst($k) ?>: </td><td>
  <?php if ($k == "archiviert") : ?>
  <input type="checkbox" name="e[<?= $k ?>]" value="1" <?= ($v ? "checked" : "") ?>>
  <?php else: ?>
  <input type="text" name="e[<?= $k ?>]" value="<?= htmlspecialchars($v) ?>">
  <?php endif; ?>
  </td></tr>
  <?php endforeach; ?>
  </table>
  
  <input type="submit" value="   Speichern   ">
  
  </td><td valign=top>
  
  <h4>Tutoren dieser Datei (Verwaltungsrechte)</h4>
  
  <select name="tutor_list[]" size=12 multiple>
  <?php foreach($Lehrer as $d): ?>
  <option value="<?= $d["lid"] ?>" <?= $d["r_did"] ? "selected" : "" ?>><?= $d["name"] ?>, <?= $d["vorname"] ?></option>
  <?php endforeach; ?>
  
  </select>
  <br>
  <small>Tipp: Um mehrere Tutoren auszuwählen "Strg" auf der Tastatur gedrückt halten.</small>
  
  </td></tr></table>
  
  </form>
  