
<h2>Übersicht für Administratoren</h2>

<style>
.bbutton { font: status-bar ; float: left; width: 220px; height: 140px; color: #555; background: #fefefe; border: 1px solid #ddd; margin: 0 10px 10px 0; }
.bbutton a { display: block; text-decoration: none; color: blue; font-size: 12pt; text-align: center; padding: 10px 10px 7px; background: #f7f7f7; border-bottom: 1px solid #ddd; }
.bbutton a:hover { background: #d6d6f8; }
.bbutton div { padding: 10px; }
.bbutton img { vertical-align: middle; }
</style>

<div class="bbutton"><a href="<?= URL_PREFIX ?>admin/lehrer_list">
<img src="<?= URL_PREFIX ?>content/icons/system-users.png"> Lehrer verwalten</a>
<div>hier können Sie: <br>
Lehrer hinzufügen<br>
Vergessene Passworte zurücksetzen
</div></div>

<div class="bbutton"><a href="<?= URL_PREFIX ?>admin/dateien">
<img src="<?= URL_PREFIX ?>content/icons/system-file-manager.png"> Dateien verwalten</a>
<div>hier können Sie: <br>
Neue Dateien erstellen<br>
Dateien kopieren<br>
Dateien archivieren (sperren)
</div></div>

<div class="bbutton"><a href="<?= URL_PREFIX ?>kurs_template/view">
<img src="<?= URL_PREFIX ?>content/icons/x-office-spreadsheet-template.png"> Kursvorlagen</a>
<div>hier können Sie: <br>
Kursvorlagen erstellen und bearbeiten
</div></div>

<div class="bbutton"><a href="<?= URL_PREFIX ?>admin/clear_editlocks" onclick="return confirm('Sind Sie sicher?')">
<img src="<?= URL_PREFIX ?>content/icons/edit-undo.png"> Sperren zurücksetzen</a>
<div>hier können Sie: <br>
im Fehlerfall alle Sperrungen gegen gleichzeitiges Bearbeiten aufheben
</div></div>


<div class="bbutton"><a href="<?= URL_PREFIX ?>install.php?load=true">
<img src="<?= URL_PREFIX ?>content/icons/preferences-system.png"> Einrichtung</a>
<div>hier können Sie: <br>
Globale Einstellungen ändern<br><br>
Hinweis: Das Super-Administrator-Kennwort wird benötigt
</div></div>

<div class="bbutton"><a href="<?= URL_PREFIX ?>admin/db_export">
<img src="<?= URL_PREFIX ?>content/icons/media-floppy.png"> Datensicherung</a>
<div>hier können Sie: <br>
alle Daten exportieren<br><br>

</div></div>

<br style="clear:left">
