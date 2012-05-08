<?php
  /**
   * M-V-C Framework main entry point
   * 
   * @package    MVC_Framework
   * @subpackage System
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
   **/
  
  /********** CONFIGURATION PART ******************************************/
  
  // D i s a b l e   D e b u g / T r a c e
  // -> Debug/Trace will append the parameters of trace() calls
  //    at the end of the output to browser
  // -> make sure this is disabled (set to true) in production use !!
  $DISABLE_TRACE =         true;
  
  // S c r i p t   R o o t
  // -> absolute path of this (index.php) file
  define("ROOT",           '/srv/www/htdocs/notendb2');
  
  // U R L   P r e f i x
  // -> the URL at which this folder is available
  // -> Ex. for URL 'http://www.example.com/noten_db/'
  //    Set to:     '/noten_db'
  define('URL_PREFIX',     '/notendb2/');
  
  // P a t h   t o   t h e   d a t a b a s e   c o n f i g u r a t i o n
  // f i l e
  // -> may be relative to ROOT
  // -> make sure this is outside the document root as this
  //    contains sensitive data !!
  define('CONFIG_FILE',    ROOT.'/../../include/db.ini');
  
  /********** END OF CONFIGURATION PART ***********************************/
  /********** DO NOT EDIT BEYOND THIS LINE ********************************/
  
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
