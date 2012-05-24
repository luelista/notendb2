<?php
  /**
   * Debug methods
   * 
   * @package    MVC_Framework
   * @subpackage System
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
   **/
	
  
  $traceContent = array();
  
  /**
   * Hinterlegen einer Debug-Ausgabe im Debugspeicher
   **/
  function trace() {
    global $traceContent,$DISABLE_TRACE;
    
    // for optimization
    if ($DISABLE_TRACE) return;
    
    $traceContent[] = print_r(func_get_args(), true);
  } 
  
  /**
   * Ausgeben des Debugspeichers (muss in der .htconfig.php aktiviert werden)
   **/
  function print_trace_output($traceContent) { 
    echo "<hr>";
    echo "<h1>Debug/Trace Output</h1>";
    echo "<pre>";
    echo htmlspecialchars(implode($traceContent,"\n"));
    echo "</pre>";
  }
  
?>