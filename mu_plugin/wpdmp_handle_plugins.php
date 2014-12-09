<?php
// error_log( 'Got here - yo..' );
if (get_option( 'wpdmp_disable_mu_plugin' ) == 'enable') {
    add_filter( 'option_active_plugins', 'wpdmp_handle_plugins' );

    function wpdmp_handle_plugins( $plugins )
    {

        if (strpos( $_SERVER['REQUEST_URI'], '/wp-admin/' ) === false) {

            // get the option for the dev mode check.
            $dev_check = get_option( 'wpdmp_plugin_check_option' );

            // if that happens to be empty, then let's just return our list of plugins.
            if ($dev_check == '') {
                return $plugins;
            }

            // defaulting to false.. if we default to true, this may break some things.
            $master_is_dev_mode = false;

            switch ($dev_check) {
                case 'wp_debug':
                    $master_is_dev_mode = WP_DEBUG;
                    break;
                case 'http_host':
                   $master_is_dev_mode = ( $_SERVER['HTTP_HOST'] == strtolower( get_option( 'wpdmp_custom_var_name' ) ) );
                    break;
                case 'apache_env_var':

                        $master_is_dev_mode = ( getenv( get_option( 'wpdmp_custom_var_name' ) ) == get_option( 'wpdmp_custom_var_value' ) );
                        //$master_is_dev_mode = get_option( 'wpdmp_custom_var_name');


                    break;
                case 'custom_php_constant':
                    $custom_var_name = get_option( 'wpdmp_custom_var_name' );
                    $constants       = get_defined_constants();
                    if (isset( $constants[$custom_var_name] ) && $constants[$custom_var_name] == 1) {
                        $master_is_dev_mode = $constants[$custom_var_name];
                    } else {
                        $master_is_dev_mode = false;
                    }
                    break;

                default:
                    $master_is_dev_mode = false;
                    break;
            }

            // if we are really in dev mode, then handle the plug-ins as we see fit!
            if ($master_is_dev_mode) {
                error_log( 'Processing plugin list..' );
                $active_plugins = get_option( 'wpdmp_plugin_dev_mode_data' );

                foreach ($plugins as $key => $value) {
                    if (in_array( $value, $active_plugins )) {
                        //error_log($key.": ".$value);
                        unset( $plugins[$key] );
                    }
                }
            }

        }

        return $plugins;
    }
}
