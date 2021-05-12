<?php

namespace Bera\Smtp;

/**
 * class FormHandler
 * 
 * @package Bera\Smtp
 */
class FormHandler 
{
    /**
     * @var Plugin $_plugin
     */
    private $_plugin;

    /**
     * @var AdminMenu $_admin_menu
     */
    private $_admin_menu;

    public function __construct( $plugin, $admin_menu ) {
        $this->_plugin = $plugin;
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

                try {
                    $this->validate_data( $settings_data );
                } catch ( \Exception $e ) {
                    \wp_redirect(
                        esc_url_raw(
                            add_query_arg(
                                array(
                                    'page' => $this->_admin_menu->get_menu_slug(),
                                    'bera_smtp_notices' => $e->getMessage(),
                                    'notice_class' => 'notice-error'
                                ),
                                admin_url('admin.php')
                            )
                        )
                    );
                    exit;
                }

                $host = sanitize_text_field($settings_data['host']);
                $username = sanitize_text_field( $settings_data['username'] );
                $password = $settings_data['password'];
                $auth = sanitize_text_field( $settings_data['auth'] );
                $port = $settings_data['port'];
                $encryption = sanitize_text_field( $settings_data['encryption'] );
               
                \update_option(
                    $this->_plugin::BERA_SMTP_SETTING,
                    array(
                        'host' => $host,
                        'username' => Helper::encrypt( $username ),
                        'password' => Helper::encrypt( $password ),
                        'auth' => $auth,
                        'port' => $port,
                        'encryption' => $encryption
                    )
                );

                \wp_redirect(
                    esc_url_raw(
                        add_query_arg(
                            array(
                                'page' => $this->_admin_menu->get_menu_slug(),
                                'bera_smtp_notices' => 'Settings saved successfully!',
                                'notice_class' => 'notice-success'
                            ),
                            admin_url('admin.php')
                        )
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
                    [
                        'From: kusjoybera@gmail.com'
                    ]
                );

                if( $is_ok ) {
                    $query_args = array(
                        'page' => $this->_admin_menu->get_email_test_menu_slug(),
                        'bera_smtp_notices' => 'Email send successfully!',
                        'notice_class' => 'notice-success'
                    );
                } else {
                    $query_args = array(
                        'page' => $this->_admin_menu->get_email_test_menu_slug(),
                        'bera_smtp_notices' => 'Email send failed.',
                        'notice_class' => 'notice-error'
                    );
                }

                \wp_redirect(
                    esc_url_raw(
                        add_query_arg(
                            $query_args,
                            admin_url('admin.php')
                        )
                    )
                );
            }
        }
    }

    /**
     * Validates form data
     * 
     * @param array $data
     * @throws Exception
     */
    private function validate_data( $data ) {
        if( empty( $data ) ) {
            throw new \Exception('Data can\'t be empty.');
        }

        if( isset( $data['auth'] ) && !empty( $data['auth'] ) ) {
            if( !in_array( $data['auth'], [ 'yes', 'no' ] ) ) {
                throw new \Exception('Wrong value for auth. please try again');
            }
        }

        if( isset( $data['encryption'] ) && !empty( $data['encryption'] ) ) {
            if( !in_array( $data['encryption'], [ 'tls', 'ssl', 'none' ] ) ) {
                throw new \Exception('Wrong value for encryption. please try again');
            }
        }
    }
}