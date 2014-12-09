jQuery( document ).ready(function() {
    jQuery('select[name="wpdmp_plugin_check_option"]').on('change', function() {

        jQuery('input[name="wpdmp_custom_var_name"]').removeAttr('disabled');
        jQuery('input[name="wpdmp_custom_var_value"]').removeAttr('disabled');

        if (jQuery(this).find('option:selected').val() == 'wp_debug') {
            jQuery('input[name="wpdmp_custom_var_name"]').attr('disabled', 'disabled');
            jQuery('input[name="wpdmp_custom_var_value"]').attr('disabled', 'disabled');

        } else if (jQuery(this).find('option:selected').val() == 'custom_php_constant') {

            jQuery('input[name="wpdmp_custom_var_value"]').attr('disabled', 'disabled');

        } else if (jQuery(this).find('option:selected').val() == 'http_host') {

            jQuery('input[name="wpdmp_custom_var_value"]').attr('disabled', 'disabled');
        }
    }).trigger('change');


});