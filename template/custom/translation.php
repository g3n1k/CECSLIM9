<?php
function e($text){
  global $base_lang;
  if(isset($base_lang) AND $base_lang!="id") {
    include "translating/{$base_lang}.php";
      if (isset($lang[$text]) && !empty($lang[$text])) {
          return $lang[$text];
      }
  }
    return $text;
}


?>
