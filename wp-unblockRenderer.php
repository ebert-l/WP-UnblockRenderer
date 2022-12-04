<?php
/*
* Plugin Name: WP-UnblockRenderer
* Description: A simple Plugin to unblock the site-rendering while loading all Styles and scripts.
* Version: 0.1
* Author: Eberthel, Leon Ebert
* Author URI: https://eberthel.de
* Text Domain: wp-unblockRenderer 
*/

/*
URL zu lokalen Dateien
plugins_url( 'myscript.js', __FILE__ );

*/

if(! function_exists('wpUR_isActive')){
    /*
    * @return true/false if Plugin is currently active
    */
    function wpUR_isActive(){
        return get_option('wpUR_Active');
    }
}

if(! function_exists('wpUR_registerOptions')){
    function wpUR_registerOptions(){
        register_setting('wpUR_options', 'wpUR_unblockStyles');
        register_setting('wpUR_options', 'wpUR_unblockScripts');
        register_setting('wpUR_options', 'wpUR_noBlockFilter');
    }
}

if(! function_exists('wpUR_setDefaultOptions')){
    function wpUR_setDefaultOptions(){
        add_option('wpUR_unblockStyles', 'true');
        add_option('wpUR_unblockScripts', 'true');
        add_option('wpUR_noBlockFilter', array());
    }
}

if(! function_exists('wpUR_style_loader_tag')){
    function wpUR_style_loader_tag($html, $handle) {
        $async_html = str_replace( 'media=\'all\'', 'media="print" onload="this.media=\'all\'; this.removeAttribute(\'onload\')"', $html);
        return $async_html;
    }
    add_filter('style_loader_tag', 'wpUR_style_loader_tag', 10, 4);
}

if(! function_exists('wpUR_style_loader_tag')){
    function wpUR_script_loader_tag($html, $handle) {
        if(str_contains($html, "bookly")){
            return str_replace('type=\'text/javascript\'', 'type="text/javascript" defer', $html);
        }
        $async_html = str_replace('type=\'text/javascript\'', 'type="text/javascript" async', $html);
        return $async_html;
    }
    add_filter('script_loader_tag', 'wpUR_script_loader_tag', 10, 4);
}

/*
* Settings Page
*/
if(! function_exists('wpUR_createSettings')){
    function wpUR_createSettings(){
        add_settings_section(
            'wpUR_settings_section',
            'WP Unblock Renderer',
            'wpUR_settings_callback',
            'wpUR_options'
        );

        add_settings_field(
            'wpUR_setting_unblockStyles',
            'Unblock Styles',
            'wpUR_settings_unblockStylesField',
            'wpUR_options',
            'wpUR_settings_section'
        );
    }
}
add_action('admin_init', 'wpUR_createSettings');

function wpUR_addMenu(){
    add_options_page(
        'Unblock Renderer',
        'WP Unblock Renderer',
        'manage_options',
        'wpur',
        'wpUR_settings_page',
    );
}
add_action('admin_init', 'wpUR_addMenu');

function wpUR_settings_page(){
    ?>
        <h1>Test</h1>
    <?php
    // include plugin_dir_path(__FILE__).'settings.php';
}

if(! function_exists('wpUR_settings_callback')){
    function wpUR_settings_callback(){
        echo '<h1>WP Unblock Renderer Settings</h1>';
    }
}

if(! function_exists('wpUR_settings_unblockStylesField')){
    function wpUR_settings_unblockStylesField(){
        ?>
        <input type="checkbox" name="wpUR_unblockStyles"></input>
        <?php
    }
}


/*
Activation, Deactivaiton and Deinstallations
*/
if(! function_exists('unblockRenderer_deactivate')){
    // Called when Plugin gets deactivated
    function unblockRenderer_deactivate(){
        delete_option('wpUR_Active');
        unregister_setting('wpUR_options', 'wpUR_Active');
    }
}

if(! function_exists('unblockRenderer_deinstall')){
    // Called when Plugin gets deinstalled
    function unblockRenderer_deinstall(){
        delete_option('wpUR_unblockStyles');
        delete_option('wpUR_unblockScripts');
        delete_option('wpUR_noBlockFilter');
        unregister_setting('wpUR_options', 'wpUR_unblockStyles');
        unregister_setting('wpUR_options', 'wpUR_unblockScripts');
        unregister_setting('wpUR_options', 'wpUR_noBlockFilter');

    }
}

if(! function_exists('unblockRenderer_activate')){
    //Called when Plugin gets activated
    function unblockRenderer_activate(){
        register_setting('wpUR_options', 'wpUR_Active');
        add_option('wpUR_Active', 'true');
        if(! function_exists('wpUR_registerOptions')){wpUR_registerOptions();}
        if(! function_exists('wpUR_setDefaultOptions')){wpUR_setDefaultOptions();}
        
        register_deactivation_hook(__FILE__, 'unblockRenderer_deactivate');
        register_uninstall_hook(__FILE__, 'unblockRenderer_deinstall');
    }
    register_activation_hook(__FILE__, 'unblockRenderer_activate');
}

if(! function_exists('wpUR_createSettings')){
    add_action('admin_init', 'wpUR_createSettings');
}


?>