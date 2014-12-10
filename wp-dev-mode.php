<?php

/**  Copyright 2014  Mark Cyrulik  (email : mcyrulik@room204.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 *
 * @link              http://example.com
 * @since             0.2.0
 * @package           WordPress Plugin Dev Mode
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Plugin Dev Mode
 * Plugin URI:        http://example.com/plugin-name-uri/
 * Description:       Allows the enabling/disabling of WordPress plugins based on one of several flags.
 * Version:           0.2.0
 * Author:            Mark Cyrulik
 * Author URI:        http://markcyrulik.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-plugin-dev-mode
 * Domain Path:       /languages
 */

defined('ABSPATH') or die();


//SETUP
function wp_plugin_dev_mode_install()
{
    // Lets create a directory that we will use to store our settings.. and other stuff.
    if (!is_dir(WP_CONTENT_DIR."/wp-plugin-dev-mode")) {
        mkdir(WP_CONTENT_DIR . "/wp-plugin-dev-mode");
        chmod(WP_CONTENT_DIR . "/wp-plugin-dev-mode", 0755);
    }

    // Now let's move our MU Plugin into place.
    if (!is_dir(WP_CONTENT_DIR."/mu-plugins")) {
        mkdir(WP_CONTENT_DIR . "/mu-plugins");
        chmod(WP_CONTENT_DIR . "/mu-plugins", 0755);
    }

    copy(__DIR__."/mu_plugin/wpdmp_handle_plugins.php", WP_CONTENT_DIR . "/mu-plugins/wpdmp_handle_plugins.php");
}

function wp_plugin_dev_mode_deactivate()
{
    unlink(WP_CONTENT_DIR . "/mu-plugins/wpdmp_handle_plugins.php");
    update_option( 'wpdmp_disable_mu_plugin', '');
}

function wp_plugin_dev_mode_menu()
{
    $hook = add_options_page('WP Plugin Dev Mode Options', 'WP Plugin Dev Mode', 'manage_options', 'wp-plugin-dev-mode-menu', 'wp_dev_mode_plugin_options');
    //call register settings function
    add_action( 'load-'.$hook, 'wp_plugin_dev_mode_options_saved' );
    add_action('admin_init', 'register_mysettings');
}


function register_mysettings()
{
    //register our settings
    register_setting('wp-plugin-dev-mode-settings-group', 'wpdmp_plugin_check_option');
    register_setting('wp-plugin-dev-mode-settings-group', 'wpdmp_custom_var_name');
    register_setting('wp-plugin-dev-mode-settings-group', 'wpdmp_plugin_dev_mode_data');
    register_setting('wp-plugin-dev-mode-settings-group', 'wpdmp_custom_var_value');
    register_setting('wp-plugin-dev-mode-settings-group', 'wpdmp_disable_mu_plugin');
}

function wp_plugin_dev_mode_action_init()
{
    // Localization
    load_plugin_textdomain('domain', false, dirname(plugin_basename(__FILE__)));
}

function wp_dev_mode_plugin_options()
{
    include( 'admin/wp-plugin-dev-mode-admin.php' );
}

function wp_plugin_dev_mode_enqueue($hook) {
    wp_enqueue_script( 'wp-plugin-dev-mode-admin', plugin_dir_url( __FILE__ ) . 'admin/includes/js/wp-plugin-dev-mode-admin.js', array('jquery') );
}

function wp_plugin_dev_mode_options_saved() {
    // If the settings were just saved, then lets process our options and write some JSON
    if ($_GET['settings-updated'] && $_GET['settings-updated'] == true) {
        $settings_array = array();

        $settings_array['wpdmp_plugin_check_option'] = get_option('wpdmp_plugin_check_option');
        $settings_array['wpdmp_custom_var_name'] = get_option('wpdmp_custom_var_name');
        $settings_array['wpdmp_plugin_dev_mode_data'] = get_option('wpdmp_plugin_dev_mode_data');
        $settings_array['wpdmp_custom_var_value'] = get_option('wpdmp_custom_var_value');
        $settings_array['wpdmp_disable_mu_plugin'] = get_option('wpdmp_disable_mu_plugin');

        if (is_writeable(WP_CONTENT_DIR."/wp-plugin-dev-mode")) {
            $fp = fopen(WP_CONTENT_DIR.'/wp-plugin-dev-mode/wp_dev_mode_plugin_options.json', 'w');
            fwrite($fp, json_encode($settings_array));
            fclose($fp);
        }
    }


}


add_action( 'admin_enqueue_scripts', 'wp_plugin_dev_mode_enqueue' );

// Add our actions.

add_action('init', 'wp_plugin_dev_mode_action_init');

// First let's register our plugin with WP..
register_activation_hook(__FILE__,'wp_plugin_dev_mode_install');

// Then the settings menu.
add_action('admin_menu','wp_plugin_dev_mode_menu');

register_deactivation_hook( __FILE__, 'wp_plugin_dev_mode_deactivate' );

