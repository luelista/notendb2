<?php
  /**
   * M-V-C Framework main entry point
   * 
   * @package    MVC_Framework
   * @subpackage System
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
   **/
  
  // load configuration
  if (!file_exists("./.htconfig.php")) {
    die("<h3>Please make sure .htconfig.php exists in main directory. The easiest way to do this is to rename .htconfig.php_template 
and edit it accordingly.</h3>");
  }
  require_once "./.htconfig.php";
  
  // configure error reporting to be independent of php.ini
  error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED); 
 
  // includes
  require_once ROOT."/system/common.php";
  require_once ROOT."/system/trace.php";
  require_once ROOT."/system/routing.php";
  require_once ROOT."/system/views.php";
  require_once ROOT."/system/models.php";
  require_once ROOT."/controller/controller.php";
  
  // load controller
  load_controller();
  
  // print trace to browser (only if not disabled at top!)
  if (! $DISABLE_TRACE) print_trace_output($traceContent);
?>
