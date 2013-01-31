
  <!-- recommend recent browser -->
  <script>
  if(!document.getElementById || (navigator.userAgent.indexOf("Firefox") < 1 && navigator.userAgent.indexOf("AppleWebKit") < 1)) {
  document.write('<div style="border:2px solid red;padding:10px;"><b>Wichtiger Hinweis:</b><br>Um diese Anwendung nutzen zu können, sollten Sie eine aktuelle Version von Mozilla Firefox oder Google Chrome verwenden!<br><br><a href="http://www.mozilla.org/de/firefox/new/">Firefox herunterladen</a><br><br><a href="https://www.google.com/intl/de/chrome/browser/">Google Chrome herunterladen</a><br><br><a href="#" onclick="showLogin(); return false;">Im aktuellen Browser fortfahren</a> (nicht empfohlen)</div>');
  } else window.browserOk=true;
  function showLogin() {
    document.getElementById("showMe").style.display="block"
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
  
  <script>if(browserOk) showLogin(); </script>

