
<?php if($Error): ?>
<h2>Fehler</h2>

<p><?= $Error ?></p>
<?php endif; ?>

<form action="<?=URL_PREFIX?><?= $MethodURL ?>?datei=<?= $DID ?>" method="post">
<table>
<?php foreach($Data as $k=>$v): ?>
<tr><td><?= ucfirst($k) ?>: </td><td><input type="input" name="e[<?= $k ?>]" value="<?= htmlspecialchars($v) ?>"></td></tr>
<?php endforeach; ?>
<tr><td></td><td><input type="submit" value="   Speichern   "></td></tr>
</table>
</form>
