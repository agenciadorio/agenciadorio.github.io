<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/**
 * Loads class files upon call
 */
define('ROOT', plugin_dir_path(__FILE__) );
spl_autoload_register('tt_login__autoloader');
function tt_login__autoloader($classname) {
    if(strpos($classname, 'tt_login') === 0) {
        $namespace = substr($classname, 0, strrpos($classname, '\\'));
        $namespace = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
        $classPath = ROOT . str_replace('\\', '/', $namespace) . '.php';
        if (is_readable($classPath)) {
            require_once $classPath;
        }
    }
}