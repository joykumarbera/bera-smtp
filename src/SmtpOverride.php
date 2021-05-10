<?php

namespace Bera\Smtp;

/**
 * class SmtpOverride
 * 
 * @package Bera\Smtp
 */
class SmtpOverride 
{
    private $_admin_menu;

    public function __construct( $admin_menu ) {
        $this->_admin_menu = $admin_menu;
    }

    public function init() {
        \add_action('phpmailer_init', array($this, 'override_phpmailer_defualt_config'));
    }

    /**
     * Override smtp settings based on settings data
     * 
     * @param PHPMailer $phpmailer
     * @since 1.0.0
     */
    public function override_phpmailer_defualt_config( $phpmailer ) {

        $smtp_config_data = $this->_admin_menu->get_settings_data();

        if( empty( $smtp_config_data ) ) {
            return;
        }
        
        $phpmailer->isSMTP();
        $phpmailer->Host = $smtp_config_data['host'];
        $phpmailer->Port = $smtp_config_data['port'];
        $phpmailer->SMTPSecure = 'tls';

        if( array_key_exists( 'auth', $smtp_config_data ) && $smtp_config_data['auth'] === 'yes' ) {
            $phpmailer->SMTPAuth = true;
            $phpmailer->Username = $smtp_config_data['username'];
            $phpmailer->Password = $smtp_config_data['password'];
        }

        if( array_key_exists( 'from', $smtp_config_data ) ) {
            $phpmailer->From = $smtp_config_data['from'];
        }
        
        if( array_key_exists( 'from_name', $smtp_config_data ) ) {
            $phpmailer->FromName = $smtp_config_data['from_data'];
        }
    }    
}