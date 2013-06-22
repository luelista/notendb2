<?php
  /**
   * M-V-C Framework main entry point
   * 
   * @package    MVC_Framework
   * @subpackage System
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <moritz.willig@gmail.com>
   **/
  
  define('ROOT', dirname(__FILE__));
  
  // configure error reporting to be independent of php.ini
  error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED); 
 
  // includes
  require_once ROOT."/system/configuration.php";
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
