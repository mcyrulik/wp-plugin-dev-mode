<?php
defined('ABSPATH') or die("No script kiddies please!");

if (file_exists(WP_CONTENT_DIR.'/wp-plugin-dev-mode/wp_dev_mode_plugin_options.json')) {
    $file_contents = file_get_contents(WP_CONTENT_DIR.'/wp-plugin-dev-mode/wp_dev_mode_plugin_options.json');
    $settings_array = json_decode($file_contents, true);
} else {
    $settings_array = null;
}

// If there is a settings_array, and our values are set then we are going to use those values, otherwise we'll get a value(if any) out of the database.
$default_plugin_state = (isset($settings_array['wpdmp_default_plugin_state']) ? $settings_array['wpdmp_default_plugin_state'] : get_option('wpdmp_default_plugin_state'));
$plugin_check_option = (isset($settings_array['wpdmp_plugin_check_option']) ? $settings_array['wpdmp_plugin_check_option'] : get_option('wpdmp_plugin_check_option'));

$disable_mu_plugin = (isset($settings_array['wpdmp_disable_mu_plugin']) ? $settings_array['wpdmp_disable_mu_plugin'] : get_option('wpdmp_disable_mu_plugin'));

$custom_var_name = (isset($settings_array['wpdmp_custom_var_name']) ? $settings_array['wpdmp_custom_var_name'] : get_option('wpdmp_custom_var_name'));
$custom_var_value = (isset($settings_array['wpdmp_custom_var_value']) ? $settings_array['wpdmp_custom_var_value'] : get_option('wpdmp_custom_var_value'));

$active_plugins = (isset($settings_array['wpdmp_plugin_dev_mode_data']) ? $settings_array['wpdmp_plugin_dev_mode_data'] : get_option('wpdmp_plugin_dev_mode_data'));
?>
<div class="wrap">
<form method="post" action="options.php" name="wp-plugin-dev-mode-options">
<h2>WP Plugin Dev Mode Plugin</h2>
<h3>WP Plugin Dev Mode Options</h3>
    <input type="checkbox" name="wpdmp_disable_mu_plugin" value="enable" <?= ($disable_mu_plugin == 'enable' ? 'checked=checked' : '') ?>>Enable the MU Plugin
    <div>
        <p>In this section you can set the overall settings of the plugin. The parameters that are defined here determine whether this particular installation of WordPress is a development environment.
        Further explanation of the options are below.</p>

        <ul>
            <li>Server HTTP Host - This will use the HTTP Host that is sent in as a header from the browser, If you choose this option, enter the url of your development site in the "Custom Value" field below.</li>
            <li>Custom PHP Constant - This will use a PHP constant that you define. The value must evaluate to true to be considered valid. False, empty, not set, etc. will all evaluate to false. You are responsible for defining and setting this constant.</li>
            <li>Custom Apache Environment Variable - Apache allows you to set environment variables in several ways. If you denote your development environment in this way, choose this option. You will need to enter not only the environment variable name but its value as well</li>
            <li>WP_DEBUG Constant - This is a standard debug constant is referenced int he WordPress Codex. This can also be used to identify this installation as a development installation. </li>
        </ul>
    </div>

    <?php settings_fields( 'wp-plugin-dev-mode-settings-group' );

    ?>
    <?php //do_settings( 'wp-plugin-dev-mode-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Method to check for development site</th>
            <td>
                <select name="wpdmp_plugin_check_option">
                    <option value=""></option>
                    <option value="http_host" <?= ($plugin_check_option == 'http_host' ? 'selected=selected' : '') ?>>Server HTTP Host</option>
                    <option value="custom_php_constant" <?= ($plugin_check_option == 'custom_php_constant' ? 'selected=selected' : '') ?>>Custom PHP Constant</option>
                    <option value="apache_env_var" <?= ($plugin_check_option == 'apache_env_var' ? 'selected=selected' : '') ?>>Custom Apache Environment Variable</option>
                    <option value="wp_debug" <?= ($plugin_check_option == 'wp_debug' ? 'selected=selected' : '') ?>>WP_DEBUG Constant</option>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Variable Name</th>
            <td><input type="text" name="wpdmp_custom_var_name" value="<?php echo $custom_var_name; ?>" <?= ($plugin_check_option == 'wp_debug' ? 'disabled=disabled' : '') ?> /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Variable Value</th>
            <td><input type="text" name="wpdmp_custom_var_value" value="<?php echo $custom_var_value; ?>" <?= ($plugin_check_option == 'wp_debug' ? 'disabled=disabled' : '') ?> /></td>
        </tr>


    </table>

    <h3>Plugins installed in Wordpress</h3>
    <p>Below is a list of the plugins that are currently installed in WordPress. By checking the box next to a plugin, this plugin will be blocked from loading</p>
    <p> </p>
    <h4>Couple things to note:</h4>
    <ul>
        <li>This does not deactivate the plugin. It simply stops it from loading when a post is rendered. This was intentional.</li>
        <li>Stopping a plugin from loading may have unintended consequences on your site - proceed with caution.</li>

    </ul>
    <table class="form-table">
        <thead>
            <th>Plugin Name and version</th>
            <th>Disable on dev?</th>
        </thead>
    <?php
    $plugin_list = get_plugins();

    foreach ($plugin_list as $id => $plugin) {
        // Taking this plugin out of the mix.
        if ($id == 'wp_dev_mode/wp-dev-mode.php') {
            continue;
        }

        if (in_array($id, $active_plugins)) {
            $deactivate_on_run = true;
        } else {
            $deactivate_on_run = false;
        }
    ?>
        <tr valign="top">
            <td scope="row">
                <?= $plugin['Title'].': '.$plugin['Version'] ?>

            </td>
            <td>
                <input type="checkbox" name="wpdmp_plugin_dev_mode_data[]" value="<?= $id ?>" <?= ($deactivate_on_run ? "checked=checked" : '') ?>>
            </td>
        </tr>

    <?php } ?>

    </table>
    <?php submit_button(); ?>

</form>

</div>