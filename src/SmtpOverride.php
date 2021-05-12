<?php

namespace Bera\Smtp;

/**
 * class SmtpOverride
 * 
 * @package Bera\Smtp
 */
class SmtpOverride 
{
    /**
     * @var AdminMenu $_plugin
     */
    private $_plugin;

    public function __construct( $plugin ) {
        $this->_plugin = $plugin;
    }

    /**
     * Init hooks
     */
    public function init() {
        \add_action('phpmailer_init', array($this, 'override_phpmailer_defualt_config'));
    }

    /**
     * Override smtp settings based on settings data
     * 
     * @param PHPMailer $phpmailer
     */
    public function override_phpmailer_defualt_config( $phpmailer ) {
        $smtp_config_data = $this->_plugin->get_settings_data();

        if( empty( $smtp_config_data ) ) {
            return;
        }
        
        $phpmailer->isSMTP();
        $phpmailer->Host = $smtp_config_data['host'];
        $phpmailer->Port = $smtp_config_data['port'];
        $phpmailer->SMTPSecure = $smtp_config_data['encryption'];

        if( array_key_exists( 'auth', $smtp_config_data ) && $smtp_config_data['auth'] === 'yes' ) {
            $phpmailer->SMTPAuth = true;
            $phpmailer->Username = $smtp_config_data['username'];
            $phpmailer->Password = $smtp_config_data['password'];
        }
    }    
}