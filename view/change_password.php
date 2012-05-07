
  <?php if($Error): ?>
  <h2>Fehler</h2>
  
  <p><?= $Error ?></p>
  <?php endif; ?>
  
  <h2>Passwort ändern</h2>
  
  <?= $InfoText ?>
  
  <form action="<?=URL_PREFIX?><?= $MethodURL ?>" method="post">
  <table>
  
  <tr><td>Altes Passwort: </td><td><input type="password" name="password"></td></tr>
  <tr><td>Passwort: </td><td><input type="password" name="new_password_1"></td></tr>
  <tr><td>Wiederholen: </td><td><input type="password" name="new_password_2"></td></tr>
  <tr><td></td><td><input type="submit" value="   Ändern   "></td></tr>
  </table>
  </form>
  