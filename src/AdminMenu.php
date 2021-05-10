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
    const BERA_SMTP_SETTING = 'bera_smtp_setting';

    /**
     * @var Bera\Smtp\Plugin $_plugin
     */
    private $_plugin;

    /**
     * @var array $_form_fields
     */
    private $_form_fields;

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
        \add_action('admin_post_' . self::SMTP_CONFIG_ACTION, array($this, 'smtp_config_form_handler') );
        \add_action('admin_post_' . self::EMAIL_TEST_ACTION, array($this, 'email_test_form_handler') );
    }

    /**
     * Send a dummy email using the config
     * 
     * @since 1.0.0
     */
    public function email_test_form_handler() {
        if( isset( $_POST[$this->_form_fields['email_test_form']['nounce_name']] ) &&
            \wp_verify_nonce( 
                $_POST[$this->_form_fields['email_test_form']['nounce_name']], 
                $this->_form_fields['email_test_form']['action'] )
        ) {
            if( isset( $_POST['bera_test_email'] ) && !empty( $_POST['bera_test_email'] ) ) {
                $email = sanitize_email( $_POST['bera_test_email'] );

                $is_ok = \wp_mail(
                    $email, 
                    'A smtp configaration test',
                    'Looks like everything is ok. now you can send email using SMTP in WP',
                );

                if( $is_ok ) {
                    \wp_redirect(
                        esc_url_raw(
                            add_query_arg(
                               array(
                                    'page' => 'bera-easy-smtp-email-test',
                                    'bera_smtp_notices' => 'Email send successfully!',
                                    'notice_class' => 'notice-success'
                               ),
                                admin_url('admin.php')
                            )
                        )
                    );
                } else {
                    \wp_redirect(
                        esc_url_raw(
                            add_query_arg(
                               array(
                                    'page' => 'bera-easy-smtp-email-test',
                                    'bera_smtp_notices' => 'Email send failed.',
                                    'notice_class' => 'notice-error'
                               ),
                                admin_url('admin.php')
                            )
                        )
                    );
                }
            }
        }
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
     * Handle smtp config form
     * 
     * @since 1.0.0
     */
    public function smtp_config_form_handler() {

        if( isset( $_POST[$this->_form_fields['smtp_form']['nounce_name']] ) &&
            \wp_verify_nonce( 
                $_POST[$this->_form_fields['smtp_form']['nounce_name']], 
                $this->_form_fields['smtp_form']['action'] )
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
                    self::BERA_SMTP_SETTING,
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

                \wp_redirect(
                    esc_url_raw(
                        add_query_arg(
                           array(
                                'page' => $this->get_menu_slug(),
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
     * Setup admistrative menus
     * 
     * @since 1.0.0
     */
    public function set_up_menus() {

        add_options_page(
            $this->get_title(),
            $this->get_title(),
            'manage_options',
            $this->get_menu_slug(),
            array($this, 'smtp_config_content')
        );

        \add_menu_page(
            $this->get_title(),
            $this->get_title(),
            'manage_options',
            $this->get_menu_slug()
        );

        \add_submenu_page(
            $this->get_menu_slug(),
            'Smtp Config',
            'Smtp Config',
            'manage_options',
            $this->get_menu_slug(),
            array($this, 'smtp_config_content')  
        );

        \add_submenu_page(
            $this->get_menu_slug(),
            'Email Test',
            'Email Test',
            'manage_options',
            $this->get_menu_slug() . '-email-test',
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

        return \implode(' ', array_map( function( $data ) {
                return \ucfirst($data);
            }, $parts)
        );
    }

    /**
     * Get menu slug
     * 
     * @return string
     * @since 1.0.0
     */
    private function get_menu_slug() {
        return $this->_plugin->get_plugin_name();
    }

    /**
     * Load email test menu content
     * 
     * @since 1.0.0
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

    /**
     * Load smtp config menu content
     * 
     * @since 1.0.0
     */
    public function smtp_config_content() {
        $current_settings_data = $this->get_settings_data();
       
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