<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>login</title>
<link rel="stylesheet" type="text/css" href="<?=URL_PREFIX?>content/style.css">
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
  <!-- recommend recent browser -->
  <script>
  if(!document.getElementById || (navigator.userAgent.indexOf("Firefox") < 1 && navigator.userAgent.indexOf("Chrome") < 1)) {
  document.write('<div style="border:2px solid red;padding:10px;"><b>Wichtiger Hinweis:</b><br>Um diese Anwendung nutzen zu können, sollten Sie eine aktuelle Version von Mozilla Firefox oder Google Chrome verwenden!</div>');
  }
  </script>
  
  <!-- recommend javascript -->
  <noscript>
  <div style="border:2px solid red;padding:10px;">
  <b>Wichtiger Hinweis:</b><br>Um diese Anwendung nutzen zu können, muss in Ihrem Browser JavaScript aktiviert sein!
  </div>
  </noscript>
  
  
  <?php if($Error): ?>
  <h2>Fehler</h2>
  
  <p><?= $Error ?></p>
  <?php endif; ?>
  
  
  <form action="<?=URL_PREFIX?>user/login" method="post" style="display:none" id="showMe">
  <h2>Bitte einloggen</h2>
  <table>
  
  <tr><td>Benutzername: </td><td><input type="text" name="username"></td></tr>
  <tr><td>Passwort: </td><td><input type="password" name="password"></td></tr>
  <tr><td></td><td><input type="submit" value="   Login   "></td></tr>
  </table>
  </form>
  
  <br><br><br>
  
  <script> document.getElementById("showMe").style.display="block" </script>
</div>
</div>

<div id="footer">
<div class="wrapper">
  Copyright (c) 2012 Max Weller, Moritz Willig
</div>
</div>



</body>
</html>
