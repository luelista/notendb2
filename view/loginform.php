<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>login</title>
<link rel="stylesheet" type="text/css" href="<?=URL_PREFIX?>view/style.css">
</head>
<body>

<div id="header">
<div class="wrapper">
  <h1>
    Noten-Verwaltung
  </h1>
</div>
</div>

<div id="content">
<div class="wrapper">
  
  <?php if($Error): ?>
  <h2>Fehler</h2>
  
  <p><?= $Error ?></p>
  <?php endif; ?>
  
  <h2>Bitte einloggen</h2>
  
  <form action="<?=URL_PREFIX?>user/login" method="post">
  <table>
  
  <tr><td>Benutzername: </td><td><input type="text" name="username"></td></tr>
  <tr><td>Passwort: </td><td><input type="password" name="password"></td></tr>
  <tr><td></td><td><input type="submit" value="   Login   "></td></tr>
  </table>
  </form>
  
  <br><br><br>
</div>
</div>

<div id="footer">
<div class="wrapper">
  Copyright (c) 2012 Max Weller, Moritz Willig
</div>
</div>



</body>
</html>
