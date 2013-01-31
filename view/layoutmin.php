<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?= $DocTitle ?> - <?= $SiteTitle ?></title>
<link rel="stylesheet" type="text/css" href="<?=URL_PREFIX?>content/style.css">
<style>
#header {
  background: <?= HEADER_BG_COLOR?>;
  background-image: linear-gradient(bottom, <?= HEADER_BG_COLOR?> 0%, <?= HEADER_BG_COLOR_2?> 100%);
  background-image: -o-linear-gradient(bottom, <?= HEADER_BG_COLOR?> 0%, <?= HEADER_BG_COLOR_2?> 100%);
  background-image: -moz-linear-gradient(bottom, <?= HEADER_BG_COLOR?> 0%, <?= HEADER_BG_COLOR_2?> 100%);
  background-image: -webkit-linear-gradient(bottom, <?= HEADER_BG_COLOR?> 0%, <?= HEADER_BG_COLOR_2?> 100%);
  background-image: -ms-linear-gradient(bottom, <?= HEADER_BG_COLOR?> 0%, <?= HEADER_BG_COLOR_2?> 100%);
  background-image: -webkit-gradient(linear,left bottom,left top,color-stop(0, <?= HEADER_BG_COLOR_1?>),color-stop(1, <?= HEADER_BG_COLOR_2?>));
}
</style>

</head>
<body>

<div id="header">
<div class="wrapper">
  <h1>
    Noten-Verwaltung
  </h1>
</div>
</div>

<div id="content">
<div class="wrapper">
  <?= $Inhalt ?>
</div>
</div>

<div id="footer">
<div class="wrapper">
  Copyright (c) 2012 Max Weller, Moritz Willig
</div>
</div>



</body>
</html>
