<?php

/**
 * @file
 * template.php
 */

function subtheme_preprocess_node(&$variables) {
  $variables['theme_hook_suggestions'][] = 'node__' . $variables['type'] . '__' . $variables['view_mode'];
}

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

global $user;
global $tag;

print_r($tag);

//  // SETTING SOME COOKIES IN ORDER TO RECOGNISE THE user
//  if(isset($COOKIE['parseId']) && $COOKIE['parseId']!=null){
//    $cookie = $COOKIE['parseId'];
//    setcookie('parseId', $cookie, time()+3600*24*60); //per 2 mesi
//  } else {
//    $parseId = rand(9999999999999,1);
//    $cookie = $parseId;
//    setcookie('parseId', $parseId, time()+3600*24*60); //per 2 mesi
//  }


//Setting array parse with variables I will need in javascript
$parse = array(
  //'parseId' => $cookie,
  'uid' => $user->uid,
  'tag' => $tag,
  'title' => drupal_get_title(),
  'debug' => false
);

drupal_add_js(array('parse'=>$parse), 'setting');

?>
