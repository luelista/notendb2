
<style type="text/css">
.kreuztabelle, .kreuztabelle th, .kreuztabelle td {
  font: status-bar;
}
.kreuztabelle th, .kreuztabelle tr.headrow th {
  background: #aaa; vertical-align: top;
}
.kreuztabelle tr.footrow th, .kreuztabelle tr.footrow td { background: #444 !important; color: #fff; text-align: center; font-weight: bold; }
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
.kreuztabelle tr th.eingereicht {
  background: #58f; 
}
.kreuztabelle tr:hover th.eingereicht {
  background: #5eb5f3 !important; 
}

@media screen {
  .kreuztabelle tr th.firstcol {
    position: absolute; width: 140px; overflow: hidden; padding: 3px 5px;
    background: #bbd; margin-left: -5px; border-right: 6px solid white;
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
  td { padding-left: 3px; }
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
input.errord { background: #ff9999 !important; }
.kreuztabelle tr th.editableColhdr {
  background-image: url(<?=URL_PREFIX?>content/edit-22.png) !important;
  background-repeat: no-repeat !important; background-position: right center !important;
}
.kreuztabelle tr th.editableColhdr a { display: block; height: 28px; color: #fff; text-decoration: none; }
.kreuztabelle tr:hover th.editableColhdr a { color: #030; }
.kreuztabelle tr th.editableColhdr:hover { background-color: #8e8!important; }
.kreuztabelle tr th.editableColhdr a:hover { color: #090; text-decoration: underline; font-weight: bold; }

</style>

<script>
  window.isEinreichen=false;
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
    $("form").submit(function() {
      var invalidNoten = false, missingNoten = 0;
      $("input.n").each(function() {
        var v = $(this).val();
        if ((!is_int(v) || parseInt(v) < 0 || parseInt(v) > 15)) {
          invalidNoten=true;$(this).addClass("errord");
        } else {
          $(this).removeClass("errord");
        }
      });
      $("input.n,input.f,input.u").each(function() { if($(this).val()=="") { missingNoten++;$(this).addClass("errord");} });
      if (invalidNoten) { alert("Bitte geben Sie nur Noten zwischen 0 und 15 ein."); return false; }
      if (missingNoten>0 && window.isEinreichen) { alert("Sie haben nicht alle Felder ausgefüllt. Bitte füllen Sie alles aus oder klicken Sie auf Speichern statt auf Einreichen."); return false; }
      if (missingNoten>0 && !window.isEinreichen) { alert("Hinweis:\nSie haben noch nicht alle Felder ausgefüllt. Bitte öffnen Sie dieses Formular später erneut, um es zu vervollständigen."); return false; }
      
      formSent = true;
    } );
    function check_inp(i) {
      var v = $(i).val();
      //if (v.length == 1) { v. }
      if ($(i) == "" || !is_int(v) || parseInt(v) < 0 || parseInt(v) > 15) $(i).addClass("errord"); else $(i).removeClass("errord");
    }
    function makeDirty() {
      formDirty = true; $("input[name=save]").css("background", "#ff4444");
    }
    function nextInput(act) {
      var inputs = $(act).parents("table").find("input");
      return inputs.filter(":gt(" + inputs.index(act) + ")").first();
    }
    $("input[type=text]").change(function() {
      check_inp(this);
      makeDirty();
    } ).each(function() { check_inp(this); });
    
    $("input[type=text], input[type=checkbox]").change(function() {
      makeDirty();
    } );
    
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

    if (tabMode == "noten_view" || tabMode == "noten_edit" || tabMode == "uebersicht") {
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
      $("input[type=text]").keydown(function(e) { if (e.keyCode==13) {e.preventDefault(); nextInput(this).focus(); return false;}  })
    }
    if (tabMode == "zuordnung_view" || tabMode == "zuordnung_edit") {
      function calcCount() {
        $("td.cnt").each(function() {
          var cnt=0, id=$(this).attr("data-kuid");
          $("input[name^='rsk_enable['][name$='][" + id + "]']:checked").each(function(){cnt++});
          $(".checked_" + id + "").each(function(){cnt++});
          $(this).html(cnt);
        }).click(function() {
          var id=$(this).attr("data-kuid");
          if (tabMode == "zuordnung_view") {
            var vals = [];
            $(".checked_" + id + ",.unchecked_"+id).each(function(){ if($(this).attr("class").substring(0,2)=="un") vals.push(0); else vals.push(1); });
            var valString=vals.join('-');
            localStorage.zuordnungClipboard = valString;
          } else {
            if (!localStorage.zuordnungClipboard) {
              alert("Bitte vorher eine Zuordnung kopieren!");
            } else {
              var vals = localStorage.zuordnungClipboard.split("-");
              $("input[name^='rsk_enable['][name$=']["+id+"]']").each(function(index) {
                $(this).attr('checked',vals[index]==1);
              })
            }
          }
        });
      }
      $("input[type=checkbox]").each(function() { var a=this;$(a).closest("td").click(function(e) {
        if(e.target.tagName!="INPUT")a.checked=!a.checked; calcCount(); makeDirty();
      });});
      calcCount();
    }
  });
  function is_int(value){
    for (i = 0 ; i < value.length ; i++) {
      if ((value.charAt(i) < '0') || (value.charAt(i) > '9')) return false 
    }
    return true;
  }
  
</script>


  <?php if($Error): ?>
  <h2>Fehler</h2>
  
  <p><?= $Error ?></p>
  <?php endif; ?>
  
  <?php if(!$ReadOnly): ?>
  <form action="<?=URL_PREFIX?><?= $MethodURL ?>?datei=<?= $DID ?>" method="post">
  <?php if($ShowEinreichen): ?>
  <input type="submit" name="save_einreichen" onclick="if(confirm('Wenn Sie alle Noten eingegeben haben, können Sie diese an den Tutor einreichen.\nAchtung: Nach dem Einreichen können Sie die Daten nicht mehr verändern, sondern müssen sich an den Tutor wenden.\n\nMöchten Sie die Noten dieses Kurses jetzt einreichen?')) isEinreichen=true; else return false;" value="      Einreichen     " style="float:right;background: lightblue;border-color: darkblue" />
  <?php endif;?>
  
  <input type="submit" name="save" value="     Daten speichern     " style="float:right;background: lightgreen;border-color: darkgreen" />
  
  <input type="button" value="     Abbrechen     " style="float:right;background:#ddd;margin-right:5px;" onclick='history.back()' />
  <?php endif; ?>

<h2><?= $Heading ? $Heading : "Kreuztabelle ansehen/bearbeiten" ?></h2>


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
<th class="<?= $colodd ? "colodd" : "" ?> <?= $e["eingereicht"]!=null ? "eingereicht" : "" ?>  <?= $e["head_lnk"] ? 'editableColhdr"><a href="'.$e["head_lnk"] : '' ?>"><?= $e["art"] ?>&nbsp;|&nbsp;<?= $e["wochenstunden"] ?>
</th>
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


<?php if($_GET["gruppiert"] != "true"): ?>
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

  <?php if($controller_function == "noten_edit"): ?>
  <tr class="headrow Hlehrer">
  <th style=text-align:right class=firstcol>&nbsp;</th>
  <th class=boguscol>&nbsp;</th>
  <!-- Lehrer -->
  <?php $colodd = false;$lastkurs=""; foreach($Kurse as $e): ?>
  <th class="<?= $colodd ? "colodd" : "" ?>">
  <nobr><input type="text" class="desc" value="Note" disabled><input type="text" class="desc" value="Fehl." disabled><input type="text" class="desc" value="Unent." disabled></nobr>
  </th>
  <?php endforeach; ?>
  <!-- Ende Lehrer -->
  <th>-</th>
  </tr>
  <?php endif; ?>
  
  <?php if($controller_function == "zuordnung_edit" || $controller_function == "zuordnung_view"): ?>
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
  <?php endif; ?>
  
<?php endif; ?>



<!-- Schueler -->
<tr<?= $odd ? " class=odd" : "" ?>>
<th class=firstcol><?= $d["name"] ?>, <?= $d["vorname"] ?></th>
<th class=boguscol>&nbsp;</th>


<?php $colodd = false;$lastkurs=""; foreach($Kurse as $ddd): if($ddd["name"]!=$lastkurs){$lastkurs=$ddd["name"];$colodd=!$colodd;} ?>
<td class="<?= $colodd ? "colodd" : "" ?>"><?= $d['reldata'][$ddd['kuid']] ?></td>
<?php endforeach; ?>

<td class="fsResult"></td>

</tr>
<!-- Ende Schueler -->
<?php endforeach; ?>




</table>
</div></div>
<div style="height:500px" class="resizeMe"></div>

  <?php if(!$ReadOnly): ?>
  </form>
  <?php endif; ?>  
