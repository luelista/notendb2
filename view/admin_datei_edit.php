
  <?php if($Error): ?>
  <h2>Fehler</h2>
  
  <p><?= $Error ?></p>
  <?php endif; ?>
  
  <form action="<?=URL_PREFIX?><?= $MethodURL ?>?datei=<?= $DID ?>" method="post">
  
  <table width=100%><tr><td valign=top>
  
  <h4>Allgemeine Informationen</h4>
  
  <table>
  <?php foreach($Data as $k=>$v): ?>
  <tr><td><?= ucfirst($k) ?>: </td><td><input type="input" name="e[<?= $k ?>]" value="<?= htmlspecialchars($v) ?>"></td></tr>
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
  
  </td></tr></table>
  
  </form>
  