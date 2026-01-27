<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://www.fromdoppler.com/
 * @since 1.0.0
 *
 * @package    Doppler_For_Woocommerce
 * @subpackage Doppler_For_Woocommerce/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php

if (! current_user_can('manage_options') ) {
    return;
}

if(isset($_GET['tab']) ) {
    $dplrwoo_active_tab = sanitize_text_field(wp_unslash($_GET['tab']));
}else{
    $dplrwoo_active_tab = 'lists';
} 

?>
<div class="dp-library">
    <div class="wrapper-loading" id="doppler-loading-screen">
            <div class="loading-page"></div>
    </div>
    <div class="dp-container" id="dplr_body_content" style="display: none;">
        <div class="dplr_settings">

            <a href="<?php esc_attr_e('https://www.fromdoppler.com/en/?utm_source=landing&utm_medium=integracion&utm_campaign=wordpress', 'doppler-for-woocommerce')?>" target="_blank" class="dplr-logo-header"><img src="<?php echo esc_url(DOPPLER_FOR_WOOCOMMERCE_URL)?>admin/img/logo-doppler.svg" alt="Doppler logo"/></a>

            <h2 class="main-title"><?php esc_html_e('Doppler for WooCommerce', 'doppler-for-woocommerce')?> <?php echo esc_html($this->get_version())?></h2> 

            <h1 class="screen-reader-text"></h1>

            <?php

            switch($dplrwoo_active_tab){

            case 'fields':
                if(isset($_POST['dplrwoo_mapping']) && is_array($_POST['dplrwoo_mapping']) && current_user_can('manage_options') && check_admin_referer('map-fields') ) {
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                    update_option('dplrwoo_mapping', $this->sanitize_text_array(wp_unslash($_POST['dplrwoo_mapping'])));
                    $this->set_success_message(__('Fields mapped succesfully', 'doppler-for-woocommerce'));
                    $this->reset_buyers_and_contacts_last_synch();
                }
                $dplrwoo_wc_fields = $this->get_checkout_fields();
                $dplrwoo_fields_resource = $this->doppler_service->getResource('fields');
                $dplrwoo_dplr_fields = $dplrwoo_fields_resource->getAllFields();
                $dplrwoo_dplr_fields = isset($dplrwoo_dplr_fields->items) ? $dplrwoo_dplr_fields->items : [];
                $dplrwoo_maps = get_option('dplrwoo_mapping');
                include_once 'mapping.php';
                break;

            default:
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                if(isset($_POST['dplr_subscribers_list']) && $this->validate_subscribers_list(wp_unslash($_POST['dplr_subscribers_list'])) && current_user_can('manage_options') && check_admin_referer('map-lists') ) {
                    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                    $dplrwoo_subscribers_lists = $this->sanitize_subscribers_list(wp_unslash($_POST['dplr_subscribers_list']));

                    update_option('dplr_subscribers_list', $dplrwoo_subscribers_lists);
                    $this->set_success_message(__('WooCommerce has been successfully connected and synchronized.', 'doppler-for-woocommerce'));
                        
                    $this->reset_buyers_and_contacts_last_synch();
                } else {
                    $dplrwoo_subscribers_lists = get_option('dplr_subscribers_list');
                }
                    
                $dplrwoo_lists = $this->get_alpha_lists();            
                    
                //Check if saved buyers & contact Lists still exists, unset them if not.
                $dplrwoo_has_to_update = false;

                if(!empty($dplrwoo_subscribers_lists['buyers']) && !$this->list_exists($dplrwoo_subscribers_lists['buyers'], $dplrwoo_lists)) {
                    $dplrwoo_has_to_update = true;
                    $dplrwoo_subscribers_lists['buyers'] = '0';
                }
                
                if(!empty($dplrwoo_subscribers_lists['contacts']) && !$this->list_exists($dplrwoo_subscribers_lists['contacts'], $dplrwoo_lists)) {
                    $dplrwoo_subscribers_lists['contacts'] = '0';
                    $dplrwoo_has_to_update = true;
                }
                        
                if($dplrwoo_has_to_update) { update_option('dplr_subscribers_list', $dplrwoo_subscribers_lists);
                }

                $dplrwoo_connection_status = $this->dplrwoo_check_status();

                if(is_array($dplrwoo_connection_status) 
                    && isset($dplrwoo_connection_status['success'])
                    && $dplrwoo_connection_status['success'] === true
                    && isset($dplrwoo_connection_status['connected'])
                    && $dplrwoo_connection_status['connected'] === false) {
                    $this->set_warning_message_title(__('WooCommerce is not connected.', 'doppler-for-woocommerce'));
                    $this->set_warning_message(__('To reconnect, click the Connect and Sync button.', 'doppler-for-woocommerce'));
                }
                else if(is_array($dplrwoo_connection_status)
                    && isset($dplrwoo_connection_status['success'])
                    && $dplrwoo_connection_status['success'] === false
                    && isset($dplrwoo_connection_status['code'])
                    && $dplrwoo_connection_status['code'] === 400) {
                    $this->set_warning_message_title(__('WooCommerce is not connected yet.', 'doppler-for-woocommerce'));
                    $this->set_warning_message(__('Select the List you want to assign and click the button to synchronize your user data.', 'doppler-for-woocommerce'));
                }
                    
                include_once 'lists.php';
                
                break;
            
            }
            ?>
            
        </div>
    </div>
</div>
