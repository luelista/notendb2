
<style type="text/css">
.kreuztabelle, .kreuztabelle th, .kreuztabelle td {
  font: status-bar;
}
.kreuztabelle th, .kreuztabelle tr.headrow th {
  background: #aaa;
}
.kreuztabelle tr {
  background: #fafafa;
}
.kreuztabelle tr.odd {
  background: #f0f0f0;
}
.kreuztabelle tr.odd td.colodd {
  background: #ddd;
}
.kreuztabelle td.colodd {
  background: #f0f0f0;
}
.kreuztabelle th.colodd {
  background: #bbb;
}
.kreuztabelle tr th.firstcol {
  position: absolute; width: 140px; overflow: hidden; padding: 3px 5px;
  background: #bbd;
}
.kreuztabelle tr.odd th.firstcol { background: #99b; }
.kreuztabelle .boguscol {
  width: 150px !important;
}
tr, tr td {
  height: 19px;
}
tr.headrow, tr.headrow td {
  height: 30px;
}

tr.headrow .firstcol {
  height: 24px;
}
.kreuztabelle {
  width: auto; table-layout:fixed
}
.kreuztabelle tr:hover th, .kreuztabelle tr:hover td { background: #afa !important; }
.wrapper {
  max-width: inherit; padding: 5px 25px;
}
.tableContainer { overflow: auto; width: 97%; height: 500px; position: absolute; }
h2 { margin: 0; padding-right: 10px; }
#footer { display: none; }
</style>

<script>
  $(function() {
    $(".tableContainer").scroll(function() {
      $(".firstcol").css("left", -$(".tableContainer table").position().left + 5 + "px");
    });
    $(".firstcol").css("left", -$(".tableContainer table").position().left + 5 + "px");
    function onResizeTab() {
      $(".resizeMe").css("height", $(window).height() - 120 + "px");
    }
    $(window).resize(onResizeTab);
    onResizeTab();
  });
  
</script>


  <?php if($Error): ?>
  <h2>Fehler</h2>
  
  <p><?= $Error ?></p>
  <?php endif; ?>
  
  <form action="<?=URL_PREFIX?><?= $MethodURL ?>?datei=<?= $DID ?>" method="post">

<input type="submit" name="save" value="     Eingegebene Daten speichern     " style="float:right" />

<h2>Kreuztabelle ansehen/bearbeiten</h2>


<div class="tableContainer resizeMe">
<div>
<table class="kreuztabelle">


<?php foreach($Schueler as $d): $odd=!$odd; ?>

<?php if($c++%15==0): ?>
<tr class="headrow">
<th style=text-align:right class=firstcol><b>Kurs</b></th>
<th class=boguscol width=150><div style="width:150px">&nbsp;</div></th>
<!-- Kurse -->
<?php
for($i = 0; $i < count($Kurse);){
  for($j = 0; $i < count($Kurse) && $Kurse[$i-$j]["name"] == $Kurse[$i]["name"]; $i++, $j++);
    echo "<th colspan=".($j).">{$Kurse[$i-1]["name"]}</th>";}
?>
<!-- Ende Kurse -->
</tr>



<tr class="headrow">
<th style=text-align:right class=firstcol><b>Kurs</b></th>
<th class=boguscol>&nbsp;</th>
<!-- Lehrer -->
<?php $colodd = false;$lastkurs=""; foreach($Kurse as $e): if($e["name"]!=$lastkurs){$lastkurs=$e["name"];$colodd=!$colodd;} ?>
<th class="<?= $colodd ? "colodd" : "" ?>"><?= $e["art"] ?>&nbsp;|&nbsp;<?= $e["wochenstunden"] ?></th>
<?php endforeach; ?>
<!-- Ende Lehrer -->
</tr>

<tr class="headrow">
<th style=text-align:right class=firstcol><b><nobr>Schüler / Lehrer</nobr></b></th>
<th class=boguscol>&nbsp;</th>
<!-- Lehrer -->
<?php $colodd = false;$lastkurs=""; foreach($Kurse as $e): if($e["name"]!=$lastkurs){$lastkurs=$e["name"];$colodd=!$colodd;} ?>
<th class="<?= $colodd ? "colodd" : "" ?>"><?= $e["lehrer_namen"] ?></th>
<?php endforeach; ?>
<!-- Ende Lehrer -->
</tr>
<?php endif; ?>



<!-- Schueler -->
<tr<?= $odd ? " class=odd" : "" ?>>
<th class=firstcol><?= $d["name"] ?>, <?= $d["vorname"] ?></th>
<th class=boguscol>&nbsp;</th>


<?php $colodd = false;$lastkurs=""; foreach($Kurse as $ddd): if($ddd["name"]!=$lastkurs){$lastkurs=$ddd["name"];$colodd=!$colodd;} ?>
<td class="<?= $colodd ? "colodd" : "" ?>"><?= $d['reldata'][$ddd['kuid']] ?></td>
<?php endforeach; ?>

</tr>
<!-- Ende Schueler -->
<?php endforeach; ?>


</table>
</div></div>
<div style="height:500px" class="resizeMe"></div>

  </form>
  
