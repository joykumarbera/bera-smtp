<?php

namespace Bera\Smtp;

/**
 * Class Plugin
 * 
 * @package Bera\Smtp
 */
class Plugin
{
    /**
     * @var string $plugin_name
     */
    private $plugin_name;

    /**
     * @var string $plugin_version
     */
    private $plugin_version;

    public function __construct() {
        $this->plugin_name = 'bera-smtp';
        $this->plugin_version = '1.0.0';
    }

    /**
     * Kick of the plugin
     * 
     * @since 1.0.0
     */
    public function run() {
        $admin_menu = new AdminMenu( $this );
        
        $admin_menu->init();
        ( new FormHandler( $admin_menu ) )->init();
        ( new SmtpOverride( $admin_menu ) )->init();
    }

    /**
     * Get the plugin name
     * 
     * @return string
     * @since 1.0.0
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Get the plugin version
     * 
     * @return string
     * @since 1.0.0
     */
    public function get_plugin_version() {
        return $this->plugin_version;
    }
}