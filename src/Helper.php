<?php

namespace Bera\Smtp;

class Helper 
{
    /**
     * Load a template
     * 
     * @param string $file
     * @param array $data
     */
    public static function load_template( $file, $data = [] ) {
        $file_name = BERA_SMTP_DIR . '/templates/' . $file . '.php';
   
        if( file_exists( $file_name ) ) {
            \extract( $data );
            include $file_name;
        }
    }

    /**
     * Encrypt data
     * 
     * @param string $data
     * @return string
     */
    public static function encrypt( $data ) {
        return \base64_encode( $data );
    }

    /**
     * Decrypt data
     * 
     * @param string $data
     * @return string
     */
    public static function decrypt( $data ) {
        return \base64_decode( $data );
    }

    /**
     * Custom redirect
     * 
     * @param array $query_args
     */
    public static function redirect( $query_args = [], $base_url = '' ) {

        if( empty( $base_url ) ) {
            
        }
        else {
            if( !empty( $query_args ) ) {
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
}