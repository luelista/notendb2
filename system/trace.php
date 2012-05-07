<?php
  /**
   * Debug methods
   * 
   * @package    MVC_Framework
   * @subpackage System
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
   **/
	
  
  $traceContent = array();
  
  function trace() {
    global $traceContent,$DISABLE_TRACE;
    
    // for optimization
    if ($DISABLE_TRACE) return;
    
    $traceContent[] = print_r(func_get_args(), true);
  } 
  
  function print_trace_output($traceContent) { 
    echo "<hr>";
    echo "<h1>Debug/Trace Output</h1>";
    echo "<pre>";
    echo htmlspecialchars(implode($traceContent,"\n"));
    echo "</pre>";
  }
  
?>