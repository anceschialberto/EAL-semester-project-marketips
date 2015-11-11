<?php

/**
 * @file
 * template.php
 */

function subtheme_theme() {
  $items = array();

  $items['user_login'] = array(
    'render element' => 'form',
    'path' => drupal_get_path('theme', 'subtheme') . '/templates',
    'template' => 'user-login',
    'preprocess functions' => array(
      'subtheme_preprocess_user_login'
    ),
  );

  return $items;
}

function subtheme_preprocess_user_login(&$vars) {
  $vars['intro_text'] = t('This is my awesome login form');
}

?>
