<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty substr modifier plugin
 *
 * Type:     modifier<br>
 * Name:     substr<br>
 * Purpose:  simple search/substr
 * @link http://smarty.php.net/manual/en/language.modifier.substr.php
 *          substr (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_strip_css($html)
{
    $search = array ("'<style[^>]*?>.*?</style>'si"); 
    $replace = array ("");
    return preg_replace ($search, $replace, $html);
}

/*
//static function cleanElements($html){
//  
//  $search = array (
//         "'<script[^>]*?>.*?</script>'si",  //remove js
//          "'<style[^>]*?>.*?</style>'si", //remove css 
//          "'<head[^>]*?>.*?</head>'si", //remove head
//         "'<link[^>]*?>.*?</link>'si", //remove link
//         "'<object[^>]*?>.*?</object>'si"
//                      ); 
//        $replace = array ( 
//              "",
//                                   "",
//              "",
//              "",
//              ""
//                      );                 
//  return preg_replace ($search, $replace, $html);
//}
*/

/* vim: set expandtab: */

?>
