<?php

  $DISABLE_TRACE = true;
  
  // load configuration
  $SITE_CONFIG = array();
  if (!file_exists(ROOT . "/.htconfig.php")) {
    die("<h3>You're redirected to the installation wizard now...</h3><script>location='".dirname($_SERVER["PHP_SELF"])."/install.php'</script>");
  }
  require_once ROOT . "/.htconfig.php";
  if ($SITE_CONFIG['INI_DONE'] != 1) {
    die("<h3>You're redirected to the installation wizard now...</h3><script>location='".dirname($_SERVER["PHP_SELF"])."/install.php'</script>");
  }
  
  // define configuration as global constants for easier access
  foreach($SITE_CONFIG as $k=>$v) define($k, $v);
  
  