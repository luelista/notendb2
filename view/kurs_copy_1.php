
<h2>Kopieren Sie Kurse - Schritt 1</h2>

<p>W&auml;hlen Sie die Quelldatei, aus der die Kurse kopiert werden sollen, aus...</p>

<p>
  <form action="<?= URL_PREFIX ?>kurs/copy_2/?datei=<?= $DID ?>" method="post" onsubmit="return checkSubm()">
  <select name="src_datei" id="src_datei">
  <option></option>
      <?php foreach($Dateien as $d): if($d["did"] == $DID) continue; ?>
      <option value="<?=$d["did"]?>"><?= $d["descr"] ?><?=(($d["tutor"])?"[".$d["tutor"]."]":"")?></option>
      <?php endforeach; ?>
  </select>
</p>

<p><input type="submit" name="next" value="  Weiter >  "></p>

</form>

<script>
  function checkSubm() {
    if ($("#src_datei").val()) {
      return true;
    } else {
      alert("Bitte eine Quelldatei auswählen.");
      return false;
    }
  }
</script>
