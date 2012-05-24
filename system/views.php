<?php
  /**
   * View manager
   * 
   * @package    MVC_Framework
   * @subpackage System
   * @author     Max Weller <max.weller@teamwiki.net>
   **/
	
  $GLOBALS["globalViewVars"] = array();
  
  /**
   * Zuweisen einer global gültigen Ansichtsvariablen
   **/
  function set_view_var($name, $value) {
    $GLOBALS["globalViewVars"][$name] = $value;
  }
  
  /**
   * Abfragen einer global gültigen Ansichtsvariablen
   **/
  function get_view_var($name) {
    if (isset($GLOBALS["globalViewVars"][$name])) return $GLOBALS["globalViewVars"][$name];
  }
  
  /**
   * Laden einer Ansicht mit unmittelbarer Anzeige
   * @param   $viewName   Name der Ansicht
   * @param   $data       Assoziatives Array mit lokal gültigen Ansichtsvariablen
   **/
  function load_view($viewName, $data) {
    extract($GLOBALS["globalViewVars"]);
    extract($data);
    include (VIEW_DIR."/".$viewName.".php");
  }
  
  /**
   * Laden einer Ansicht mit Rückgabe des erzeugten Quelltextes
   * @param   $viewName   Name der Ansicht
   * @param   $data       Assoziatives Array mit lokal gültigen Ansichtsvariablen
   **/
  function get_view($viewName, $data) {
    ob_start();
    load_view($viewName, $data);
    return ob_get_clean();
  }
  
?>