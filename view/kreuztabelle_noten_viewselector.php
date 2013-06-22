<div style="float:right;background:#afa;padding: 2px 8px;margin-left: 10px;">
Anzeigen: 
<select id="viewMode" onchange="if(this.value=='alle_Kurse'&&!warnMsg()){ this.reset(); }else{ nav(); }">
  <option value="meine_Kurse" <?= $_GET["viewMode"] == "meine_Kurse" ? "selected" : "" ?>>Eigene Kurse</option>
  <option value="Tutorengruppe" <?= $_GET["viewMode"] == "Tutorengruppe" ? "selected" : "" ?>>Meine Tutorengruppe</option>
  <option value="alle_Kurse" <?= $_GET["viewMode"] == "alle_Kurse" ? "selected" : "" ?>>Alle Kurse</option>
</select>
<input onchange="nav();" type="checkbox" <?= $_GET["gruppiert"] == "true" ? "checked" : "" ?> id="gruppiert" value="true"><label for="gruppiert">gruppiert</label>
<input onchange="view_fehlstd();" type="checkbox" id="view_fehlstd" checked><label for="view_fehlstd" title="Fehlstunden anzeigen">Fehl.</label>

<?php if($_GET["viewMode"] == "Tutorengruppe"): ?>
<select onchange="nav();" id="t_grp">
<?php foreach($TutorengruppenListe as $d) echo "<option value=\"$d[kuid]\" ".($d["kuid"]==$_GET["t_grp"]?"selected":"").">$d[art] $d[name]</option>"; ?>
</select>
<?php endif; ?>
<script>
  function warnMsg() {
    return confirm('Sind Sie sicher, dass Sie alle Schüler und Kurse (nicht nur die Ihrer Tutorengruppe) sehen und ggf. bearbeiten möchten?');
  }
  function  nav() {
    location.search='?datei='+ScriptInfo.Datei.did+'&viewMode='+$('#viewMode').val()+'&gruppiert='+($('#gruppiert')[0].checked?'true':'false')+($("#t_grp").size()>0?'&t_grp='+$("#t_grp").val():'');
  }
  function view_fehlstd() {
    if (!$("#view_fehlstd")[0].checked) {
      $("span.u,span.f,span.s").hide();
      $(".Hkursname span").each(function() {
        $(this).text($(this).text().substring(0,3));
      })
    } else {
      $("span.u,span.f,span.s").show();
    }
  }
  
</script>
</div>
