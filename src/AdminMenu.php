<?php

namespace Bera\Smtp;

/**
 * class AdminMenu
 * 
 * @package Bera\Smtp
 */
class AdminMenu 
{
    const SMTP_CONFIG_ACTION = 'bera_smtp_config_action';
    const EMAIL_TEST_ACTION = 'bera_email_test_action';
 
    /**
     * @var Bera\Smtp\Plugin $_plugin
     */
    private $_plugin;

    /**
     * @var array $_form_fields
     */
    public $_form_fields;

    public function __construct( $plugin ) {
        $this->_plugin = $plugin;
        $this->_form_fields = array(
            'smtp_form' => array(
                'action' => self::SMTP_CONFIG_ACTION,
                'nounce_name' => 'bera_smtp_nounce'
            ),
            'email_test_form' => array(
                'action' => self::EMAIL_TEST_ACTION,
                'nounce_name' => 'bera_smtp_email_test_nounce'
            )
        );
    }

    /**
     * Init hooks
     * 
     * @since 1.0.0
     */
    public function init() {
        \add_action('admin_notices', array($this, 'show_admin_notices'));
        \add_action('admin_menu', array($this, 'set_up_menus') );
    }

    /**
     * Show a admin notice message
     * 
     * @since 1.0.0
     */
    public function show_admin_notices() {
        if( isset( $_GET['bera_smtp_notices'] ) && !empty( $_GET['bera_smtp_notices'] ) ) {
            ?>
                <div class="notice <?php echo ( isset( $_GET['notice_class'] ) ? \htmlspecialchars($_GET['notice_class']) : '' ) ?> is-dismissible">
                    <p><?php echo \htmlspecialchars( $_GET['bera_smtp_notices'] ) ?></p>
                </div>
            <?php
        }
    }

    /**
     * Setup admistrative menus
     * 
     * @since 1.0.0
     */
    public function set_up_menus() {
        \add_menu_page(
            $this->get_title(),
            $this->get_title(),
            'manage_options',
            $this->get_menu_slug()
        );

        \add_submenu_page(
            $this->get_menu_slug(),
            'SMTP Config',
            'SMTP Config',
            'manage_options',
            $this->get_menu_slug(),
            array($this, 'smtp_config_content')  
        );

        \add_submenu_page(
            $this->get_menu_slug(),
            'Email Test',
            'Email Test',
            'manage_options',
            $this->get_email_test_menu_slug(),
            array($this, 'email_menu_content')  
        );
    }

    /**
     * Get menu title
     * 
     * @return string
     * @since 1.0.0
     */
    private function get_title() {
        $plugin_name = $this->_plugin->get_plugin_name();
        $parts = \explode('-', $plugin_name);

        return \implode(' ', array( ucfirst( $parts[0] ), strtoupper( $parts[1] ) ));
    }

    /**
     * Get menu slug
     * 
     * @return string
     */
    public function get_menu_slug() {
        return $this->_plugin->get_plugin_name();
    }

    /**
     * Get email test menu slug
     * 
     * @return string
     */
    public function get_email_test_menu_slug() {
        return $this->get_menu_slug() . '-email-test';
    }

    /**
     * Load email test menu content
     */
    public function email_menu_content() {
        return Helper::load_template(
            'email-test',
            [
                'form_action' => admin_url('admin-post.php'),
                'action' => $this->_form_fields['email_test_form']['action'],
                'nounce_name' => $this->_form_fields['email_test_form']['nounce_name'],
            ]
        );
    }

    /**
     * Load smtp config menu content
     */
    public function smtp_config_content() {
        $current_settings_data = $this->_plugin->get_settings_data();
        
        return Helper::load_template(
            'smtp-config',
            [
                'form_action' => admin_url('admin-post.php'),
                'action' => $this->_form_fields['smtp_form']['action'],
                'nounce_name' => $this->_form_fields['smtp_form']['nounce_name'],
                'current_form_data' => ( is_array($current_settings_data) && !empty($current_settings_data) ) ? $current_settings_data : null
            ]
        );
    }
}