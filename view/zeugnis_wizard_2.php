
<style type="text/css">
.kreuztabelle, .kreuztabelle th, .kreuztabelle td {
  font: status-bar; padding: 0;
}
.boguscol { display: none }
.resizeMe { height: auto !important; }
table { overflow: hidden; table-layout: fixed; white-space: nowrap; width: 0px; }
td, th { overflow: hidden; white-space: nowrap; }
td, th { width: 3em; }
.headrow th { height: 8em; }
.headrow th div { transform:rotate(-90deg); -moz-transform:rotate(-90deg); -webkit-transform:rotate(-90deg); width: 8em; margin-left: -2.6em; margin-top: -4px }
td.firstcol, th.firstcol { width: 12em; }
table { border-collapse:collapse; }
table,th, td { border: 1px solid #888; }
td {text-align:center}
td.firstcol {text-align:left}
.wrapper {
  max-width: inherit; padding: 5px 25px;
}
</style>

<h3>Zeugnisdruck für Tutorengruppe: <?= $TutorengruppeName ?></h3>

<form action="<?= URL_PREFIX ?>tabelle/zeugnis_preview_xls?datei=<?= $DID ?>" method="post" style="float:right">

<input type="hidden" name="exp_name" value="<?= $TutorengruppeName ?> Übersicht">
<input type="hidden" name="kuid" value="<?= $Kuid ?>">
<input type="submit" name="export_xls" value="  Vorschau als Excel-Mappe exportieren  ">
</form>

<h2><span style="color: #777">Schritt 3:</span> Vorschau</h2>


<table class="kreuztabelle">


<thead>
<tr class="headrow Hkursinfo">
<th class=firstcol><b>Kurs/Lehrer</b><br><br>Schüler</th>
<!-- Lehrer -->
<?php foreach($Kurse as $e): ?>
<th><div><?= $e["art"] ?>&nbsp;<?= $e["name"] ?><br><?= $e["lehrer_namen"] ?></div></th>
<?php endforeach; ?>
<!-- Ende Lehrer -->

<th class=firstcol><br><b>Schüler</b></th>

<th><div>Fehl | Unent.</div></th>

</tr>
</thead>
<tbody>
<?php foreach($Schueler as $d): $odd=!$odd; ?>



<!-- Schueler -->
<tr>
<th class=firstcol><?= $d["name"] ?>, <?= $d["vorname"] ?></th>

<?php foreach($Kurse as $ddd): ?>
<td><?= $d['reldata'][$ddd['kuid']] ?></td>
<?php endforeach; ?>

<th class=firstcol><?= $d["name"] ?>, <?= $d["vorname"] ?></th>

<td><?= $d["summe"] ?></td>

</tr>
<!-- Ende Schueler -->
<?php endforeach; ?>
</tbody>

<tfoot>
<!-- Fusszeile -->
<tr class="footrow">
<th class=firstcol>Anzahl Schüler</th>

<?php foreach($Kurse as $ddd): ?>
<td><?= $ddd["anzahl"] ?></td>
<?php endforeach; ?>

<td>&nbsp;</td>

</tr>
<!-- Ende Fusszeile -->
</tfoot>

</table>

<hr>
<div class="noprint" style="float:right;width:160px;padding-left: 10px; border-left: 1px solid #bbb;">
  <br><br>
  Download der Vorlagen:<br>
  <a href="<?= URL_PREFIX ?>content/Fertige%20Vorlagen/BG12_Vorlage.doc">BG12_Vorlage.doc</a>
  <a href="<?= URL_PREFIX ?>content/Fertige%20Vorlagen/BG13_Vorlage.doc">BG13_Vorlage.doc</a>
  <br>
    <small style="color:red"><b>Neu 06.02.2013: Verbesserte Vorlagen mit korrekter Fachrichtung</b></small><br>
  <br><br><br>
  <small><b>Tipp:</b><br>
  Bemerkungen können über die Schülerliste ergänzt werden.
  </small>
</div>

<h2 class="noprint"><span style="color: #777">Schritt 4a:</span> Export für Seriendruck</h2>

<p class="noprint">Wenn die oben ausgegebenen Daten korrekt sind, klicken Sie bitte auf die Schaltfläche
"Daten Exportieren". Die resultierende Datei können Sie abspeichern und mit der
Word-Seriendruck-Funktion laden, um daraus Zeugnisse zu generieren.


</p>
<form action="<?= URL_PREFIX ?>tabelle/zeugnis_3?datei=<?= $DID ?>" method="post" target="_blank" class="noprint">

<input type="hidden" name="exp_name" value="<?= $TutorengruppeName ?>">
<input type="hidden" name="kuid" value="<?= $Kuid ?>">
<input type="submit" name="export" value="  Als CSV Exportieren  ">
<input type="submit" name="export_xls" value="  Als Excel-Mappe Exportieren  ">


<h2 class="noprint"><span style="color: #777">...oder<br>Schritt 4b:</span> Export als PDF</h2>
<p>
Alternativ können Sie die Zeugnisse direkt als PDF-Datei exportieren.
<br><br>
Fußzeile: <input type="text" name="footer" style="width:200px" value="Wetzlar, den <?= date("d.m.Y") ?>"><br>
<input type="submit" name="export_pdf" value="  Vorschau  ">
<input type="submit" name="export_pdf_save" value="  Speichern...  ">
</p>
</form>
</p>


