
<h2>Übersicht für Administratoren</h2>

<style>
.bbutton { font: status-bar ; float: left; width: 200px; height: 140px; color: #555; background: #fefefe; border: 1px solid #ddd; margin: 0 10px 10px 0; }
.bbutton a { display: block; font-size: 12pt; text-align: center; padding: 10px; background: #f7f7f7; border-bottom: 1px solid #ddd; }
.bbutton div { padding: 10px; }
</style>

<div class="bbutton"><a href="<?= URL_PREFIX ?>admin/lehrer_list">Lehrer verwalten</a>
<div>hier können Sie: <br>
Lehrer hinzufügen<br>
Vergessene Passworte zurücksetzen
</div></div>

<div class="bbutton"><a href="<?= URL_PREFIX ?>admin/dateien">Dateien verwalten</a>
<div>hier können Sie: <br>
Neue Dateien erstellen<br>
Dateien kopieren<br>
Dateien archivieren (sperren)
</div></div>

<div class="bbutton"><a href="<?= URL_PREFIX ?>kurs_template/view">Kursvorlagen</a>
<div>hier können Sie: <br>
Kursvorlagen erstellen und bearbeiten
</div></div>

<div class="bbutton"><a href="<?= URL_PREFIX ?>admin/clear_editlocks" onclick="return confirm('Sind Sie sicher?')">Sperren zurücksetzen</a>
<div>hier können Sie: <br>
im Fehlerfall alle Sperrungen gegen gleichzeitiges Bearbeiten aufheben
</div></div>


<div class="bbutton"><a href="<?= URL_PREFIX ?>install.php?load=true">Einrichtung</a>
<div>hier können Sie: <br>
Globale Einstellungen ändern<br><br>
Hinweis: Das Super-Administrator-Kennwort wird benötigt
</div></div>

<br style="clear:left">
