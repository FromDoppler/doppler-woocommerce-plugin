<?php
if (! defined('ABSPATH') ) { exit; // Exit if accessed directly
}

/**
 * This class handles all functionality related
 * to the checkout.
 * 
 * @since      1.2.4
 * @package    Doppler_For_Woocommerce
 * @subpackage Doppler_For_Woocommerce/includes
 * @author     Doppler LLC <info@fromdoppler.com>
 */
class Doppler_For_WooCommerce_Checkout
{
    const CONSENT_ID = 'doppler/consent-checkbox';

    public function doppler_add_email_optin_checkbox($checkout)
    {
        if (!function_exists('woocommerce_register_additional_checkout_field')) {
            return;
        }

        $consent_text = get_option('dplr_wc_consent_text', __('I agree to receive promotional emails.', 'doppler-for-woocommerce'));
        $consent_location = get_option('dplr_wc_consent_location', 'contact');

        woocommerce_register_additional_checkout_field(
            [
                'id' => self::CONSENT_ID,
                'location' => $consent_location,
                'type' => 'checkbox',
                'label' => $consent_text,
                'optionalLabel' => $consent_text,
                'required' => false
            ]   
        );
    }

    public function doppler_set_email_optin_checkbox_value($key, $value, $group, $wc_object){
        if ( self::CONSENT_ID !== $key ) {
            return;
        }

		$wc_object->update_meta_data( '_doppler_consent_key', $value, true );
        $wc_object->save();
    }
}

