<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link  https://www.fromdoppler.com/
 * @since 1.0.0
 *
 * @package Doppler_For_Woocommerce
 */

// If uninstall not called from WordPress, then exit.
if (! defined('WP_UNINSTALL_PLUGIN') ) {
    exit;
}

// Remove all plugin options and plugin tables from database.
// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Core handles nonce on uninstall.
$dplrwoo_requested_plugin = isset($_REQUEST['plugin']) ? sanitize_text_field(wp_unslash($_REQUEST['plugin'])) : '';
if($dplrwoo_requested_plugin === ( plugin_basename(__DIR__) . '/doppler-for-woocommerce.php' ) ) {

    $dplrwoo_options = array(
    'dplr_subscribers_list',
    'dplrwoo_mapping',
    'dplrwoo_use_hub',
    'dplrwoo_version',
    'dplrwoo_api_connected',
    'dplrwoo_notice_field'
    );
    
    array_map('dplrwoo_uninstall_options', $dplrwoo_options);

    //Delete abandoned cart table on uninstall.
    global $wpdb;
    $dplrwoo_table_name = esc_sql($wpdb->prefix . 'dplrwoo_abandoned_cart');
    // phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery -- Uninstall cleanup.
    // phpcs:disable WordPress.DB.DirectDatabaseQuery.NoCaching -- Uninstall cleanup.
    // phpcs:disable WordPress.DB.DirectDatabaseQuery.SchemaChange -- Uninstall cleanup.
    // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared -- Table name built from trusted prefix/constant.
    // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Table name built from trusted prefix/constant.
    $dplrwoo_result = $wpdb->query("DROP TABLE IF EXISTS {$dplrwoo_table_name}");
    
    $dplrwoo_table_name = esc_sql($wpdb->prefix . 'dplrwoo_visited_products');
    $wpdb->query("DROP TABLE IF EXISTS {$dplrwoo_table_name}");
    // phpcs:enable
}

function dplrwoo_uninstall_options($option_name)
{
    delete_option($option_name);
    delete_site_option($option_name);
}
