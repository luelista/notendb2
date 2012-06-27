
<h2>Kopieren Sie Kurse - Schritt 2</h2>

<p>W&auml;hlen Sie die Kurse aus, die kopiert werden sollen, indem Sie den Haken davor setzen...</p>


<form action="<?= URL_PREFIX ?>kurs/copy_2/" method="post">


<?php foreach($Kurse as $d): ?>
<input type="checkbox" name="copy_kuid[]" value="<?= $d["kuid"] ?>" id="k_<?=$d["kuid"]?>"><label for="k_<?=$d["kuid"]?>">
<?= $d["art"] ?> <?= $d["name"] ?> (<?= $d["lehrer_namen"] ?>)</label><br>
<?php endforeach; ?>

<p>
<input type="button" onclick="location='<?= URL_PREFIX ?>kurs/copy_1/?datei=<?= $DID ?>'" value="  < Zurück  ">
<input type="submit" name="next" value="  Kopieren >  ">
</p>

</form>
