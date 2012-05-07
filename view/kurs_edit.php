
  <?php if($Error): ?>
  <h2>Fehler</h2>
  
  <p><?= $Error ?></p>
  <?php endif; ?>
  
  <form action="<?=URL_PREFIX?><?= $MethodURL ?>?datei=<?= $DID ?>" method="post">
  
  <table width=100%><tr><td valign=top>
  
  <h4>Allgemein</h4>
  
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
  
  <h4>Zugeordnete Schüler</h4>
  
  <?php foreach($Schueler as $d): ?>
  <li><?= $d["name"] ?>, <?= $d["vorname"] ?></li>
  <?php endforeach; ?>
  
  
  </td></tr></table>
  
  </form>
  