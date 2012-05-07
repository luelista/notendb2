
<style type="text/css">
.kreuztabelle {
  width: auto; table-layout:fixed
}
.kreuztabelle, .kreuztabelle th, .kreuztabelle td {
  font: status-bar;
}
.kreuztabelle th {
  background: #aaa;
}
.kreuztabelle tr {
  background: #f5f5f5;
}
.kreuztabelle tr.odd {
  background: #ddd;
}
.wrapper {
  max-width: inherit; padding: 5px 25px;
}
.tableContainer { overflow: auto; width: 100%; height: 500px; }
</style>


  <?php if($Error): ?>
  <h2>Fehler</h2>
  
  <p><?= $Error ?></p>
  <?php endif; ?>
  
  <form action="<?=URL_PREFIX?><?= $MethodURL ?>?datei=<?= $DID ?>" method="post">

<input type="submit" name="save" value="     Eingegebene Daten speichern     " style="float:right" />

<h2>Kreuztabelle ansehen/bearbeiten</h2>

<div class="tableContainer">
<div>
<table class="kreuztabelle">


<?php foreach($Schueler as $d): $odd=!$odd; ?>

<?php if($c++%15==0): ?>
<tr>
<th style=text-align:right><b>Kurs</b></th>
<!-- Kurse -->
<?php
for($i = 0; $i < count($Kurse);){
  for($j = 0; $i < count($Kurse) && $Kurse[$i-$j]["name"] == $Kurse[$i]["name"]; $i++, $j++);
    echo "<th colspan=".($j).">{$Kurse[$i-1]["name"]}</th>";}
?>
<!-- Ende Kurse -->
</tr>



<tr>
<th style=text-align:right><b>Kurs</b></th>
<!-- Lehrer -->
<?php foreach($Kurse as $e): ?>
<th><?= $e["art"] ?> | <?= $e["wochenstunden"] ?></th>
<?php endforeach; ?>
<!-- Ende Lehrer -->
</tr>

<tr>
<th style=text-align:right><b><nobr>Schüler / Lehrer</nobr></b></th>
<!-- Lehrer -->
<?php foreach($Kurse as $e): ?>
<th><?= $e["lehrer_namen"] ?></th>
<?php endforeach; ?>
<!-- Ende Lehrer -->
</tr>
<?php endif; ?>



<!-- Schueler -->
<tr<?= $odd ? " class=odd" : "" ?>>
<th><?= $d["name"] ?>, <?= $d["vorname"] ?></th>


<?php foreach($Kurse as $ddd):?>
<td><?= $d['reldata'][$ddd['kuid']] ?></td>
<?php endforeach; ?>

</tr>
<!-- Ende Schueler -->
<?php endforeach; ?>


</table>
</div></div>

  </form>
  