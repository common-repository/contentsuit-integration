<?php

/*
Plugin Name: ContentSuit Integration
Plugin URI: https://www.contentsuit.com/integrations/wordpress
Description: With this plug-in you can integrate your WordPress website with any of ContentSuit features.
Version: 1.2
Author: ContentSuit
Author URI: https://www.contentsuit.com
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Credits: ContentSuit (http://www.contentsuit.com) 
*/

if (!defined('ABSPATH')) {
  die('No direct access allowed');
}

class ContentSuit {

  /**
   * To define the plug-in name.
   * @var string $plugin 
   */
  protected $plugin = 'ContentSuit';

  /**
   * To define the plug-in version.
   * @var string $plugin 
   */
  protected $version = '1.0';

  /**
   * Default assets path.
   * @var string $assets
   */
  protected $assets = '//assets.contentsuit.com/';

  /**
   *  Options to share with the content view.
   * @var array $options 
   */
  protected $options = array();

  /**
   * Parameters to define the titles and descriptions.
   * @var array $params 
   */
  protected $params = array();
  
  /**
   * Tag weight is the position that will be add.
   * @var int $weight 
   */
  protected $weight = 999;

  /**
   * ContentSuit constructor function.
   * 
   */
  function __construct() {
    // Add link on plugin to admin menu.
    add_action('admin_menu', array($this, 'addAdminMenu'));
    // Params, to be shown on the plugin page.
    $this->setParams();

    // Filter POST global variable.
    $post = filter_input(INPUT_POST, 'contentsuit', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    // Check, the form is submitted.
    if (isset($post['submit'])) {
      // Update values into database.
      $this->updateValues($post['key']);
    }

    // Get saved key data from database.
    $key = maybe_unserialize(get_option('contentsuit_plugin_key'));
    // Get header local or remote scripts and styles.
    if (false == empty($key)) {
      // Render tags.
      $this->renderTags($key);

      // Function to set values into the global options variables.
      $this->setValues($key);
    }
  }

  /**
   * Function add the link on page plugin to admin menu
   * 
   * @access public
   */
  public function addAdminMenu() {
    add_options_page($this->plugin, $this->plugin, 'manage_options', basename(__FILE__), array(
        $this,
        'viewOptionsPage',
    ));
  }

  /**
   * Upload the plug-in interface template
   * 
   * @access public
   */
  public function viewOptionsPage() {
    include (plugin_dir_path(__FILE__) . '/content.php');
  }

  /**
   * Update values into database.
   * 
   * @access protected
   * @param string $value
   */
  protected function updateValues($value) {
    // Update with new key.
    update_option('contentsuit_plugin_key', maybe_serialize($value));
    // Reset style key.
    update_option('contentsuit_plugin_css', null);
    // Reset javascript key.
    update_option('contentsuit_plugin_js', null);
  }

  /**
   * Function to set content parameters.
   * 
   * @access protected
   */
  protected function setParams() {
    // Params, to be shown on the plug-in page.
    $this->params = array(
        'csstags'   => 'Style TAG found, added and working.',
        'jstags'    => 'Javascript TAG found, added and working.',
        'notags'    => 'No valid TAGs were found for this key.',
        'label'     => 'Enter the key related to your domain in the field below:',
        'save'      => 'Save TAG key',
        'update'    => 'Update TAG key',
        'intro'     => 'Do not have a key?',
        'content'   => 'Please go to ContentSuit website and choice the features you need.',
        'request'   => 'Request features',
        'problem'   => 'Some issue?',
        'solution'  => 'If you have any problems or questions. Contact our team:',
        'email'     => 'support@contentsuit.com'
    );
  }

  /**
   * Function to set values into the global options variables.
   * 
   * @access protected
   * @param string $value
   */
  protected function setValues($value) {
    // Set key into the options.
    $this->options['key'] = $value;
    // Set style into the options.
    $this->options['css'] = get_option('contentsuit_plugin_css');
    // Set javascript into the options.
    $this->options['js']  = get_option('contentsuit_plugin_js');
  }

  /**
   * Function to validate if files exists.
   * 
   * @access protected
   * @param string $key
   * @param string $file
   * @return boolean
   */
  protected function connectAPI($key, $file) {
    // Set HTTPS into the heaader request.
    $apiURL = 'https:' . $this->assets . $key . '/' . $file;
    // Get files header.
    $headers = get_headers($apiURL, 1);

    // Check if header returns 404.
    if (strpos($headers[0], '404') === false) {
      return true;
    }
    return false;
  }

  /**
   * Function to check if some style file is already added.
   * 
   * @access protected
   * @param string $key
   * @param string $file
   * @return mixed
   */
  protected function getCSS($key, $file = 'styles.css') {
    // Get saved data from database and check if is added.
    if (false === empty(get_option('contentsuit_plugin_css'))) {
      return $file;
    }
    // Check if file exists to add.
    if ($this->connectAPI($key, $file)) {
      // Update database value to add.
      update_option('contentsuit_plugin_css', $file);

      return $file;
    }
    return false;
  }

  /**
   * Function to check if some javascript file is already added.
   * 
   * @access protected
   * @param string $key
   * @param string $file
   * @return mixed
   */
  protected function getJS($key, $file = 'scripts.js') {
    // Get saved data from database and check if is added.
    if (false === empty(get_option('contentsuit_plugin_js'))) {
      return $file;
    }
    // Check if file exists to get.
    if ($this->connectAPI($key, $file)) {
      // Update database value to get.
      update_option('contentsuit_plugin_js', $file);

      return $file;
    }
    return false;
  }

  /**
   * Function to create the style TAG.
   * 
   * @access public
   * @return string stylesheet TAG
   */
  public function tagCSS() {
    // Path to file.
    $path = $this->assets . $this->options['key'] . '/' . $this->options['css'];

    // Register and enqueue styles.
    wp_register_style($this->options['key'], $path, array(), $this->version);
    wp_enqueue_style($this->options['key']);
  }

  /**
   * Function to create the javascript TAG.
   * 
   * @access public
   * @return string javascript TAG
   */
  public static function tagJS() {
    // Path to file.
    $path = $this->assets . $this->options['key'] . '/' . $this->options['js'];

    // Register and enqueue scipts.
    wp_register_script($this->options['key'], $path, array(), $this->version);
    wp_enqueue_script($this->options['key']);
  }

  /**
   * Header register and enqueue scripts and styles.
   * 
   * @access protected
   * @param string $key
   */
  protected function renderTags($key) {
    // Check if is needed get a style tag.
    if ($this->getCSS($key)) {
      // WP function to enqueue styles tag to head.
      add_action('wp_enqueue_scripts', array($this, 'tagCSS'), $this->weight);
    }

    // Check if is needed get a javascript tag.
    if ($this->getJS($key)) {
      // WP function to enqueue script tag to head.
      add_action('wp_enqueue_scripts', array($this, 'tagJS'), $this->weight);
    }
  }

}

// Instance ContentSuit class function.
new ContentSuit();