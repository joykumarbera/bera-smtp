<?php

/**
 * Plugin Name: Bera SMTP
 * Version : 1.0.0
 * Plugin URI: http://bera.dev/plugins/bera-smtp
 * Author: Joy Kumar Bera
 * Author URI: http://bera.dev
 * Description: A plugin for send email using SMTP server
 * Text Domain: bera-smtp
 */


if( !file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    die();
}

define('BERA_SMTP_DIR' , __DIR__ );

require_once __DIR__ . '/vendor/autoload.php';

use Bera\Smtp\Plugin;

function bera_smtp_plugin_init() {
    $plugin = new Plugin();
    $plugin->run();
}

/**
 * Kick of the plugin
 */
bera_smtp_plugin_init();