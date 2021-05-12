<?php

namespace Bera\Smtp;

/**
 * Class Plugin
 * 
 * @package Bera\Smtp
 */
class Plugin
{
    const BERA_SMTP_SETTING = 'bera_smtp_setting';

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
        ( new FormHandler( $this, $admin_menu ) )->init();
        ( new SmtpOverride( $this ) )->init();
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

    /**
     * Get current settings data
     * 
     * @return mixed
     */
    public function get_settings_data() {
        $current_settings_data = \get_option(self::BERA_SMTP_SETTING);

        if( is_array( $current_settings_data ) && !empty( $current_settings_data ) ) {
            foreach( $current_settings_data as $key => $value ) {
                if( $key == 'username' || $key == 'password' ) {
                    $current_settings_data[$key] = Helper::decrypt( $value );
                } else {
                    continue;
                }
            }
        }

        return $current_settings_data;
    }
}