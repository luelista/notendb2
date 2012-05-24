<?php
  /**
   * Routing and controller management
   * 
   * @package    MVC_Framework
   * @subpackage System
   * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
   **/
	
  define ("CONTROLLER_DIR", ROOT."/controller");
  define ("MODEL_DIR", ROOT."/model");
  define ("VIEW_DIR", ROOT."/view");
  
  /**
   * Aufsplitten einer URL nach Schrägstrichen unter Berücksichtigung der Standardwerte für
   * nicht angegebene URL-Bestandteile
   */
  function split_url($url) {
    $p = strpos($url, "?");
    if ($p !== false) {
      parse_str(substr($url, $p + 1), $_GET);
      $url = substr($url, 0, $p);
    }
    $q = explode("/", $url);
    if (count($q) == 0 || !$q[0]) $q = array("default", "index");
    elseif ((count($q) == 1) || $q[1]=="") $q = array($q[0], "index");
    return $q;
  }
  
  /**
   * Laden des durch die URL angegebenen Controllers und Aufrufen der entsprechenden
   * Controller-Function
   */
  function load_controller() {
    $url = $_SERVER["REQUEST_URI"];
    if (substr($url, 0, strlen(URL_PREFIX)) == URL_PREFIX) $url = substr($url, strlen(URL_PREFIX));
    $parts = split_url($url);
    
    $controller_class = preg_replace("/[^a-z0-9]/", "", $parts[0]);
    $controller_function = preg_replace("/[^a-z0-9_]/", "", $parts[1]);
    
    set_view_var("controller_class", $controller_class);
    set_view_var("controller_function", $controller_function);
    
    #echo CONTROLLER_DIR."/".$controller.".php";
    if (file_exists(CONTROLLER_DIR."/".$controller_class.".php")) {
      include(CONTROLLER_DIR."/".$controller_class.".php");
      
      $controller = ucfirst($controller_class)."Controller";
      $class = new $controller;
      
      if (!method_exists($class, $controller_function)) {
        include(VIEW_DIR."/error_404.php");
      } else {
        call_user_func_array(array($class, $controller_function), array_slice($parts, 2));
      }

    } else {
      include(VIEW_DIR."/error_404.php");
    }
    
  }
  
?>