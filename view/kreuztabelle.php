
<style type="text/css">
.kreuztabelle, .kreuztabelle th, .kreuztabelle td {
  font: status-bar;
}
.kreuztabelle th, .kreuztabelle tr.headrow th {
  background: #aaa;
}
.kreuztabelle tr.footrow th, .kreuztabelle tr.footrow td { background: #444 !important; color: #fff; text-align: center; }
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
@media screen {
  .kreuztabelle tr th.firstcol {
    position: absolute; width: 140px; overflow: hidden; padding: 3px 5px;
    background: #bbd;
  }
  .kreuztabelle tr.odd th.firstcol { background: #99b; }
  .kreuztabelle .boguscol {
    width: 150px !important;
  }
  .kreuztabelle {
    width: auto; table-layout:fixed
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
  .kreuztabelle tr:hover th, .kreuztabelle tr:hover td { background: #afa !important; }
  .wrapper {
    max-width: inherit; padding: 5px 25px;
  }
  .tableContainer { overflow: auto; width: 97%; height: 500px; position: absolute; }
}
@media print {
  .boguscol { display: none }
  .resizeMe { height: auto !important; }
  table { overflow: hidden; table-layout: fixed; white-space: nowrap; width: 0px; }
  td, th { overflow: hidden; white-space: nowrap; }
  td, .Hlehrer th, .Hkursinfo th { width: 40px; }
  .Hkursname th span { overflow: hidden; display: block; width: 44px; }
  .firstcol { width: 150px; }
}
h2 { margin: 0; padding-right: 10px; }
#footer { display: none; }
</style>

<script>
  var tabMode = "<?=$controller_function ?>";
  $(function() {
    $(".tableContainer").scroll(function() {
      $(".firstcol").css("left", -$(".tableContainer table").position().left + 5 + "px");
    });
    $(".firstcol").css("left", -$(".tableContainer table").position().left + 5 + "px");
    function onResizeTab() {
      $(".resizeMe").css("height", $(window).height() - 120 + "px");
    }
    $(window).resize(onResizeTab);
    var formSent = false, formDirty = false;
    $("form").submit(function() { formSent = true } );
    $("input").change(function() { formDirty = true } );
    $(window).bind("beforeunload", function() {
      if (formDirty && !formSent) {
        e = e || window.event;
        // For IE and Firefox prior to version 4
        if (e) e.returnValue = 'ACHTUNG - Die Tabelle enthält noch ungespeicherte Änderungen!';
        // For Chrome, Safari and Opera 12+
        return 'ACHTUNG - Die Tabelle enthält noch ungespeicherte Änderungen!';
      }
    });
    onResizeTab();

    if (tabMode == "noten" || tabMode == "uebersicht") {
      function calcFehlstd() {
        $("tr").each(function(el) {
          var sumF = 0, sumU = 0;
          $(this).find("input.f").each(function() { sumF += +$(this).val(); });
	  $(this).find("input.u").each(function() { sumU += +$(this).val(); });
	
	  $(this).find("span.f").each(function() { sumF += +$(this).text(); });
          $(this).find("span.u").each(function() { sumU += +$(this).text(); });
	  
	  $(this).find(".fsResult").html("<nobr><b>" + sumF + " | " + sumU + "</b></nobr>");
        });
      }
      calcFehlstd();
      
      $("input.f, input.u").change(function() { calcFehlstd(); });
    }
    if (tabMode == "zuordnung") {
      function calcCount() {
        $("td.cnt").each(function() {
          var cnt=0, id=$(this).attr("data-kuid");
          $("input[name^='rsk_enable['][name$='][" + id + "]']:checked").each(function(){cnt++});
          $(this).html(cnt);
        });
      }
      $("input[type=checkbox]").each(function() { var a=this;$(a).closest("td").click(function(e) {
        if(e.target.tagName!="INPUT")a.checked=!a.checked; calcCount();
      });});
      calcCount();
    }
  });
  
</script>


  <?php if($Error): ?>
  <h2>Fehler</h2>
  
  <p><?= $Error ?></p>
  <?php endif; ?>
  
  <form action="<?=URL_PREFIX?><?= $MethodURL ?>?datei=<?= $DID ?>" method="post">

<input type="submit" name="save" value="     Eingegebene Daten speichern     " style="float:right;background: lightgreen;border-color: darkgreen" />

<input type="button" value="     Ansicht einstellen...     " style="float:right;background:#ddd" onclick='location.href="<?= URL_PREFIX ?>tabelle/einstellungen/<?= $controller_function ?>?datei=<?= $DID ?>"' />

<h2>Kreuztabelle ansehen/bearbeiten</h2>


<div class="tableContainer resizeMe">
<div>
<table class="kreuztabelle">


<?php foreach($Schueler as $d): $odd=!$odd; ?>

<?php if($c++%15==0): ?>

<tr class="headrow Hkursinfo">
<th style=text-align:right class=firstcol><b>Kurs</b></th>
<th class=boguscol>&nbsp;</th>
<!-- Lehrer -->
<?php $colodd = false;$lastkurs=""; foreach($Kurse as $e): if($e["name"]!=$lastkurs){$lastkurs=$e["name"];$colodd=!$colodd;} ?>
<th class="<?= $colodd ? "colodd" : "" ?>"><?= $e["art"] ?>&nbsp;|&nbsp;<?= $e["wochenstunden"] ?></th>
<?php endforeach; ?>
<!-- Ende Lehrer -->

<th>-</th>

</tr>

<tr class="headrow Hkursname">
<th style=text-align:right class=firstcol><b>Kurs</b></th>
<th class=boguscol width=150><div style="width:150px">&nbsp;</div></th>
<!-- Kurse -->
<?php
for($i = 0; $i < count($Kurse);){
  for($j = 0; $i < count($Kurse) && $Kurse[$i-$j]["name"] == $Kurse[$i]["name"]; $i++, $j++);
    echo "<th colspan=".($j)."><span>{$Kurse[$i-1]["name"]}</span></th>";}
?>
<!-- Ende Kurse -->

<th>-</th>

</tr>




<tr class="headrow Hlehrer">
<th style=text-align:right class=firstcol><b><nobr>Schüler / Lehrer</nobr></b></th>
<th class=boguscol>&nbsp;</th>
<!-- Lehrer -->
<?php $colodd = false;$lastkurs=""; foreach($Kurse as $e): if($e["name"]!=$lastkurs){$lastkurs=$e["name"];$colodd=!$colodd;} ?>
<th class="<?= $colodd ? "colodd" : "" ?>"><?= $e["lehrer_namen"] ?></th>
<?php endforeach; ?>
<!-- Ende Lehrer -->

<th>-</th>

</tr>
<?php endif; ?>



<!-- Schueler -->
<tr<?= $odd ? " class=odd" : "" ?>>
<th class=firstcol><?= $d["name"] ?>, <?= $d["vorname"] ?></th>
<th class=boguscol>&nbsp;</th>


<?php $colodd = false;$lastkurs=""; foreach($Kurse as $ddd): if($ddd["name"]!=$lastkurs){$lastkurs=$ddd["name"];$colodd=!$colodd;} ?>
<td class="<?= $colodd ? "colodd" : "" ?>"><?= $d['reldata'][$ddd['kuid']] ?></td>
<?php endforeach; ?>

<td class="fsResult">-</td>

</tr>
<!-- Ende Schueler -->
<?php endforeach; ?>


<!-- Fusszeile -->
<tr class="footrow">
<th class=firstcol>Anzahl</th>
<th class=boguscol>&nbsp;</th>

<?php $colodd = false;$lastkurs=""; foreach($Kurse as $ddd): if($ddd["name"]!=$lastkurs){$lastkurs=$ddd["name"];$colodd=!$colodd;} ?>
<td class="cnt" data-kuid="<?= $ddd['kuid'] ?>">0</td>
<?php endforeach; ?>

<td>&nbsp;</td>

</tr>
<!-- Ende Fusszeile -->


</table>
</div></div>
<div style="height:500px" class="resizeMe"></div>

  </form>
  
