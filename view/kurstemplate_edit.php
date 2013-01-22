
<h2>Kursvorlage bearbeiten...</h2>

<?php if($Error): ?>
<h2>Fehler</h2>

<p><?= $Error ?></p>
<?php endif; ?>

<form action="<?=URL_PREFIX?><?= $MethodURL ?>?datei=<?= $DID ?>" method="post">
<table>
<tr><th>Feld</th><th>Inhalt</th><th>Beispiel/Beschreibung</th></tr>

<tr><td>Schulform: </td><td><input type="input" name="e[schulform]" value="<?= htmlspecialchars($Data["schulform"]) ?>"></td><td>BG</td></tr>
<tr><td>Stufe: </td><td><input type="input" name="e[stufe]" value="<?= htmlspecialchars($Data["stufe"]) ?>"></td><td>11; 12; 13</td></tr>
<tr><td>Hj: </td><td><input type="input" name="e[hj]" value="<?= htmlspecialchars($Data["hj"]) ?>"></td><td>1; 2</td></tr>
<tr><td>Name: </td><td><input type="input" name="e[name]" value="<?= htmlspecialchars($Data["name"]) ?>"></td><td>Mathematik; Deutsch</td></tr>
<tr><td>Gewichtung: </td><td><input type="input" name="e[gewichtung]" value="<?= htmlspecialchars($Data["gewichtung"]) ?>"></td><td>(momentan nicht verwendet)</td></tr>
<tr><td>Art: </td><td><input type="input" name="e[art]" value="<?= htmlspecialchars($Data["art"]) ?>"></td><td>LK; GK; eGK</td></tr>
<tr><td>Wochenstunden: </td><td><input type="input" name="e[wochenstunden]" value="<?= htmlspecialchars($Data["wochenstunden"]) ?>"></td><td>5</td></tr>
<tr><td>Thema: </td><td><input type="input" name="e[thema]" value="<?= htmlspecialchars($Data["thema"]) ?>"></td><td>Stochastik; Individuum und Gesellschaft</td></tr>
<tr><td>Anzeigeposition: </td><td><input type="input" name="e[display_position]" value="<?= htmlspecialchars($Data["display_position"]) ?>"></td><td>(Reihenfolge in Tabelle)</td></tr>
<tr><td>Exportposition: </td><td><input type="input" name="e[export_position]" value="<?= htmlspecialchars($Data["export_position"]) ?>"></td><td>(Zeile im Zeugnis)</td></tr>
<tr><td>Fachrichtung: </td><td><input type="input" name="e[fachrichtung]" value="<?= htmlspecialchars($Data["fachrichtung"]) ?>"></td><td>(optional, legt Fachrichtung des Kursteilnehmers fest)</td></tr>

<tr><td></td><td><input type="submit" value="   Speichern   "></td></tr>
</table>
</form>
