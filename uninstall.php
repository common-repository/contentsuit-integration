<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
  exit();
}
delete_option('contentsuit_plugin_key');
delete_option('contentsuit_plugin_css');
delete_option('contentsuit_plugin_js');
