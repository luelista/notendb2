<?php
  /**
   * View manager
   * 
   * @package    MVC_Framework
   * @subpackage System
   * @author     Max Weller <max.weller@teamwiki.net>
   **/
	
  $GLOBALS["globalViewVars"] = array();
  function set_view_var($name, $value) {
    $GLOBALS["globalViewVars"][$name] = $value;
  }
  function get_view_var($name) {
    if (isset($GLOBALS["globalViewVars"][$name])) return $GLOBALS["globalViewVars"][$name];
  }
  
  function load_view($viewName, $data) {
    extract($GLOBALS["globalViewVars"]);
    extract($data);
    include (VIEW_DIR."/".$viewName.".php");
  }
  function get_view($viewName, $data) {
    ob_start();
    load_view($viewName, $data);
    return ob_get_clean();
  }
  
?>