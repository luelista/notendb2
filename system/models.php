<?php
  /**
   * Model loader
   * 
   * @package    MVC_Framework
   * @subpackage System
   * @author     Max Weller <max.weller@teamwiki.net>
   **/
	
  $loadedModels = array();
  
  /**
   * Laden eines Models
   **/
  function load_model($model) {
    global $loadedModels;
    require_once MODEL_DIR."/".$model.".php";
    $class = ucfirst($model)."Model";
    if (!isset($loadedModels[$class])) $loadedModels[$class] = new $class();
    return $loadedModels[$class];
  }
  
  /**
   * Laden einer für ein Model benötigten Hilfsdatei oder einer
   * Elternklasse eines vererbten Unter-Models
   **/
  function require_model($model) {
    global $loadedModels;
    require_once MODEL_DIR."/".$model.".php";
  }
  
	/**
   * Abstract Base Class for Models
   *
   * @package    MVC_Framework
   * @subpackage Abstracts
   * @author     Max Weller <max.weller@teamwiki.net>
   **/
	class Model {}
?>