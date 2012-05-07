
<link rel="stylesheet" type="text/css" href="http://trirand.com/blog/jqgrid/themes/redmond/jquery-ui-1.8.1.custom.css">
<link rel="stylesheet" type="text/css" href="http://static.teamwiki.net/inc/jquery.jqGrid-4.3.1/css/ui.jqgrid.css">
<script type="text/javascript" src="http://static.teamwiki.net/inc/jquery.jqGrid-4.3.1/js/jquery-1.5.2.min.js"></script>
<script type="text/javascript" src="http://static.teamwiki.net/inc/jquery.jqGrid-4.3.1/js/jquery.jqGrid.src.js"></script>


<style type="text/css">
.kreuztabelle, .kreuztabelle th, .kreuztabelle td {
  font: status-bar;
}
/*.kreuztabelle th {
  background: #aaa;
}
.kreuztabelle tr {
  background: #f5f5f5;
}
.kreuztabelle tr.odd {
  background: #ddd;
}*/
.wrapper {
  max-width: inherit; padding: 5px 25px;
}
</style>


  <?php if($Error): ?>
  <h2>Fehler</h2>
  
  <p><?= $Error ?></p>
  <?php endif; ?>
  
  <form action="<?=URL_PREFIX?><?= $MethodURL ?>?datei=<?= $DID ?>" method="post">


<div id="tableContainer" class="kreuztabelle">
<table id="gfrc2">

</table>
</div>


  </form>
  
  <?php
  $colNames = $colModel = array();
  $colNames[]= "Schüler";
  $colModel[]= array("name" => "schueler", "index" => "schueler", "width" => 160, "align" => "center", "frozen" => true);
  foreach($Kurse as $d) {
    $colNames[]= $d["lehrer_namen"];
    $colModel[]= array("name" => "rsk_$d[kuid]", "index" => "rsk_$d[kuid]", "width" => 60, "align" => "center", "formatter" => "checkbox", "edittype"=> 'checkbox', "editoptions" => array("value"=> 'Yes:No', "defaultValue"=> 'Yes'));
  }
  ?>
  <script type="text/javascript">
  jQuery("#gfrc2").jqGrid({
   	
	datatype: "local",	
   colNames:<?=json_encode($colNames)?>,
   colModel:<?=json_encode($colModel)?>,
    width:$(window).width()-60,
    viewrecords: true,
    sortorder: "desc",
	shrinkToFit: false,
	rownumbers: true,
	height: 450
});
<?php

$groupHeaders = array();
for($i = 0; $i < count($Kurse);$i=$j){
  for($j = $i; $j < count($Kurse) && $Kurse[$j]["name"] == $Kurse[$i]["name"]; $j++);
    $groupHeaders[] = array("startColumnName" => "rsk_".$Kurse[$i]["kuid"], "numberOfColumns" => $j-$i, "titleText" => $Kurse[$i]["name"]);}


?>
jQuery("#gfrc2").jqGrid('setGroupHeaders', {
  useColSpanStyle: false, 
  groupHeaders:<?= json_encode($groupHeaders) ?>
});


jQuery("#gfrc2").jqGrid('setFrozenColumns');

var mydata = <?= json_encode($Schueler) ?>;
for(var i = 0; i < mydata.length; i++)
  jQuery("#gfrc2").jqGrid('addRowData',i+1,mydata[i]);



  </script>
  