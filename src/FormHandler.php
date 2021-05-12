<?php

namespace Bera\Smtp;

class FormHandler 
{
    /**
     * @var AdminMenu $_admin_menu
     */
    private $_admin_menu;

    public function __construct( $admin_menu ) {
        $this->_admin_menu = $admin_menu;
    }

    /**
     * Init hooks
     */
    public function init() {
        \add_action('admin_post_' . $this->_admin_menu::SMTP_CONFIG_ACTION, array($this, 'smtp_config_form_handler') );
        \add_action('admin_post_' . $this->_admin_menu::EMAIL_TEST_ACTION, array($this, 'email_test_form_handler') );
    }

    /**
     * Handle smtp config form
     */
    public function smtp_config_form_handler() {

        if( isset( $_POST[$this->_admin_menu->_form_fields['smtp_form']['nounce_name']] ) &&
            \wp_verify_nonce( 
                $_POST[$this->_admin_menu->_form_fields['smtp_form']['nounce_name']], 
                $this->_admin_menu->_form_fields['smtp_form']['action'] )
        ) {
            if( isset($_POST['bera_smtp']) ) {
                $settings_data = $_POST['bera_smtp'];

                $host = sanitize_text_field($settings_data['host']);
                $username = sanitize_text_field( $settings_data['username'] );
                $password = $settings_data['password'];
                $auth = sanitize_text_field( $settings_data['auth'] );
                $port = $settings_data['port'];
                $encryption = sanitize_text_field( $settings_data['encryption'] );
                $from = sanitize_email( $settings_data['from'] );
                $from_name = sanitize_text_field( $settings_data['from_name'] );

                \update_option(
                    $this->_admin_menu::BERA_SMTP_SETTING,
                    array(
                        'host' => $host,
                        'username' => Helper::encrypt( $username ),
                        'password' => Helper::encrypt( $password ),
                        'auth' => $auth,
                        'port' => $port,
                        'encryption' => $encryption,
                        'from' => $from,
                        'from_name' => $from_name
                    )
                );

                Helper::redirect(
                    array(
                        'page' => $this->_admin_menu->get_menu_slug(),
                        'bera_smtp_notices' => 'Settings saved successfully!',
                        'notice_class' => 'notice-success'
                    )
                );
            }
        }
    }

    /**
     * Send a dummy email using the config
     */
    public function email_test_form_handler() {
        if( isset( $_POST[$this->_admin_menu->_form_fields['email_test_form']['nounce_name']] ) &&
            \wp_verify_nonce( 
                $_POST[$this->_admin_menu->_form_fields['email_test_form']['nounce_name']], 
                $this->_admin_menu->_form_fields['email_test_form']['action'] )
        ) {
            if( isset( $_POST['bera_test_email'] ) && !empty( $_POST['bera_test_email'] ) ) {
                $email = sanitize_email( $_POST['bera_test_email'] );

                $is_ok = \wp_mail(
                    $email, 
                    'A smtp configaration test',
                    'Looks like everything is ok. now you can send email using SMTP in WP',
                );

                if( $is_ok ) {
                    $query_args = array(
                        'page' => 'bera-easy-smtp-email-test',
                        'bera_smtp_notices' => 'Email send successfully!',
                        'notice_class' => 'notice-success'
                    );
                } else {
                    $query_args = array(
                        'page' => 'bera-easy-smtp-email-test',
                        'bera_smtp_notices' => 'Email send failed.',
                        'notice_class' => 'notice-error'
                    );
                }
                
                Helper::redirect( $query_args );
            }
        }
    }
}