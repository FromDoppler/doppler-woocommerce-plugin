<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link    https://www.fromdoppler.com/
 * @since   1.0.0
 * @package Doppler_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Doppler for WooCommerce
 * Plugin URI:        https://www.fromdoppler.com/
 * Description:       Connect your WooCommerce customers with your Doppler Lists.
 * Version:           1.6.2
 * Author:            Doppler LLC
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       doppler-for-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC') ) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('DOPPLER_FOR_WOOCOMMERCE_VERSION', '1.6.2'); 
define('DOPPLER_FOR_WOOCOMMERCE_URL', plugin_dir_url(__FILE__));
define('DOPPLER_FOR_WOOCOMMERCE_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('DOPPLER_FOR_WOOCOMMERCE_PLUGIN', plugin_basename(__FILE__));
if(!defined('DOPPLER_PLUGINS_PATH')) { define('DOPPLER_PLUGINS_PATH', plugin_dir_path(__DIR__));
}
if(!defined('DOPPLER_ABANDONED_CART_TABLE')) { define('DOPPLER_ABANDONED_CART_TABLE', 'dplrwoo_abandoned_cart');
}
if(!defined('DOPPLER_VISITED_PRODUCTS_TABLE')) { define('DOPPLER_VISITED_PRODUCTS_TABLE', 'dplrwoo_visited_products');
}
if(!defined('DOPPLER_WOO_API_URL')) { define('DOPPLER_WOO_API_URL', 'https://restapi.fromdoppler.com/');
}
//if(!defined( 'DOPPLER_WOO_API_URL' )) define('DOPPLER_WOO_API_URL', 'http://newapiqa.fromdoppler.net/');
if(!defined('DOPPLER_FOR_WOOCOMMERCE_ORIGIN')) { define('DOPPLER_FOR_WOOCOMMERCE_ORIGIN', 'WooCommerce');
}

/*
if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    include_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}
if ( is_admin() && !is_plugin_active( 'doppler-form/doppler-form.php' ) )  {
    $error_message = '<p style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen-Sans,Ubuntu,Cantarell,\'Helvetica Neue\',sans-serif;font-size: 13px;line-height: 1.5;color:#444;">' . esc_html__( 'This plugin requires ', 'doppler-for-woocommerce' ) . '<a href="' . esc_url( 'https://wordpress.org/plugins/doppler-form/' ) . '" target="_blank">Doppler Forms</a>' . esc_html__( ' plugin to be active.', 'doppler-for-woocommerce' ) . '</p>';
    deactivate_plugins( plugin_basename( __FILE__ ) );
    die( $error_message ); // WPCS: XSS ok.
}*/

/**
 * Class for displaying admin notices through redirects.
 */
require plugin_dir_path(__FILE__) . 'includes/class-doppler-for-woocommerce-admin-notice.php';

/**
 * Class that handle's integration with app trough api.
 */
require plugin_dir_path(__FILE__) . 'includes/class-doppler-for-woocommerce-app-connect.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-doppler-for-woocommerce-activator.php
 */
function doppler_for_woocommerce_activate()
{
    
    if (current_user_can('activate_plugins') && ! class_exists('WooCommerce') ) {
        // Deactivate the plugin.
        deactivate_plugins(plugin_basename(__FILE__));

        // Throw an error in the WordPress admin console.
        $error_message = sprintf(
        '<p style="font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,Oxygen-Sans,Ubuntu,Cantarell,\'Helvetica Neue\',sans-serif;font-size:13px;line-height:1.5;color:#444;">%s <a href="%s" target="_blank" rel="noopener">%s</a> %s</p>',
        esc_html__('This plugin requires', 'doppler-for-woocommerce'),
        esc_url('https://wordpress.org/plugins/woocommerce/'),
        esc_html('WooCommerce'),
        esc_html__('plugin to be active.', 'doppler-for-woocommerce')
        );

        $allowed = array(
        'p' => array('style' => true),
        'a' => array('href' => true, 'target' => true, 'rel' => true),
        );

        wp_die( wp_kses( $error_message, $allowed ) );
    }else{
        include_once plugin_dir_path(__FILE__) . 'includes/class-doppler-for-woocommerce-activator.php';
        Doppler_For_Woocommerce_Activator::activate();
    }

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-doppler-for-woocommerce-deactivator.php
 */
function doppler_for_woocommerce_deactivate()
{
    include_once plugin_dir_path(__FILE__) . 'includes/class-doppler-for-woocommerce-deactivator.php';
    Doppler_For_Woocommerce_Deactivator::deactivate();
}


register_activation_hook(__FILE__, 'doppler_for_woocommerce_activate');
register_deactivation_hook(__FILE__, 'doppler_for_woocommerce_deactivate');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-doppler-for-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function doppler_for_woocommerce_run()
{

    $plugin = new Doppler_For_Woocommerce();
    $plugin->run();

}

require plugin_dir_path(__FILE__) . 'includes/class-doppler-for-woocommerce-dependency-check.php';
$dplrwoo_dependency_checker = new DPLRWOO_Dependecy_Checker();

if($dplrwoo_dependency_checker->check()) {
    doppler_for_woocommerce_run();
}else{
    $dplrwoo_dependency_checker->display_warning();
}
