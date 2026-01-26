<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if (! current_user_can('manage_options') ) {
    return;
}
?>

<div class="dplr-tab-content">
    <header class="hero-banner">
        <div class="dp-container">
            <div class="dp-rowflex">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <h2><?php esc_html_e('Fields Mapping', 'doppler-for-woocommerce')?></h2>
                </div>
                <div class="col-sm-7">
                    <p>
                        <?php esc_html_e('Send the information of your Contacts in WooCommerce to Doppler. To do this, select the Doppler Field equivalent to each of the WooCommerce Fields.', 'doppler-for-woocommerce'); ?>
                        <br />
                        <?php esc_html_e('Need to create Custom Fields in Doppler?', 'doppler-for-woocommerce'); ?>
                        <a href="<?php esc_attr_e('https://help.fromdoppler.com/en/how-to-create-a-customized-field?utm_source=landing&utm_medium=integracion&utm_campaign=woocommerce', 'doppler-for-woocommerce')?>" class="green-link"><?php esc_html_e('Learn how', 'doppler-for-woocommerce')?></a>.
                    </p>
                </div>
            </div>
            <span class="arrow"></span>
        </div>
    </header>

    <div class="dp-container">
        <?php $this->display_success_message() ?>
        <?php $this->display_error_message() ?>
    </div>

    <form id="dplrwoo-form-mapping" action="" method="post">
    <?php wp_nonce_field('map-fields');?>

    <?php
    $dplrwoo_maps? $dplrwoo_used_fields = array_filter($dplrwoo_maps): $dplrwoo_used_fields = array();

    if(is_array($dplrwoo_wc_fields)) {

        foreach($dplrwoo_wc_fields as $dplrwoo_fieldtype=>$dplrwoo_arr){

            if($dplrwoo_fieldtype!='' && $dplrwoo_fieldtype!='order' && (count($dplrwoo_arr)>0) ) :

                ?>
                <table class="grid panel col-sm-12 col-md-12 col-lg-12 dp-box-shadow">
                    <thead>
                        <tr class="panel-header">
                            <th colspan="2" class="text-white semi-bold">
                                <?php
                                switch($dplrwoo_fieldtype){
                                case 'billing':
                                    esc_html_e('Billing fields', 'doppler-for-woocommerce');
                                    break;
                                case 'shipping':
                                    esc_html_e('Shipping fields', 'doppler-for-woocommerce');
                                    break;
                                case 'account':
                                    esc_html_e('Account fields', 'doppler-for-woocommerce');
                                    break;
                                case 'product':
                                    esc_html_e('Last purchase fields', 'doppler-for-woocommerce');
                                    break;
                                default:
                                    echo esc_html($dplrwoo_fieldtype);
                                    break;
                                }
                                ?>
                            </th>
                        </tr>
                        <tr>
                                <th class="mapping-th-left text-left pt-1 pb-1"><?php esc_html_e('WooCommerce Fields', 'doppler-for-woocommerce') ?></th>
                                <th class="text-left pt-1 pb-1"><?php esc_html_e('Doppler Fields', 'doppler-for-woocommerce') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                
                <?php

                foreach($dplrwoo_arr as $dplrwoo_fieldname=>$dplrwoo_fieldAtributes){
                    isset($dplrwoo_fieldAtributes['type'])? $dplrwoo_woo_field_type = $dplrwoo_fieldAtributes['type'] : $dplrwoo_woo_field_type = 'string';
                    if($dplrwoo_fieldname!=='billing_email') :
                        ?>
                            <tr>
                                <td>
                                    <?php 
                                    echo esc_html($dplrwoo_fieldAtributes['label']);
                                    ?>
                                </td>
                                <td>
                                    <div class="awa-form">
                                        <div class="dp-select">
                                            <span class="dropdown-arrow"></span>
                                            <select class="dplrwoo-mapping-fields"
                                                name="dplrwoo_mapping[<?php echo esc_attr($dplrwoo_fieldname)?>]"
                                                data-type="<?php if (isset($dplrwoo_fieldAtributes['type']))
                                                { echo esc_attr($dplrwoo_fieldAtributes['type']); } ?>">
                                                <option></option>
                                                <?php 
                                                foreach ($dplrwoo_dplr_fields as $dplrwoo_field){
                                                    
                                                    if(($this->check_field_type($dplrwoo_woo_field_type, $dplrwoo_field->type) && is_array($dplrwoo_used_fields) && !in_array($dplrwoo_field->name, $dplrwoo_used_fields)) || (is_array($dplrwoo_maps) && $dplrwoo_maps[$dplrwoo_fieldname] === $dplrwoo_field->name) ) {
                                                        ?>
                                                        <option value="<?php echo esc_attr($dplrwoo_field->name)?>" <?php if(is_array($dplrwoo_maps) && $dplrwoo_maps[$dplrwoo_fieldname] === $dplrwoo_field->name ) { echo 'selected'; 
                                                                    } ?> data-type="<?php echo esc_attr($dplrwoo_field->type) ?>">
                                                            <?php echo esc_html($dplrwoo_field->name)?>
                                                        </option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        
                        <?php
                    endif;
                }

                ?>
                    </tbody>
                </table>

                <?php

            endif;
        }

    }

    ?>
        </tbody>

    </table>

    <div class="dp-group-buttons">
        <a href="?page=doppler_woocommerce_menu&tab=lists">
        <button type="button" class="dp-button button-medium secondary-grey">
            <?php esc_html_e('Back', 'doppler-for-woocommerce') ?>
        </button>
        </a>

        <button id="dplrwoo-mapping-btn" class="dp-button button-medium primary-green">
            <?php esc_html_e('Save and Synchronize', 'doppler-for-woocommerce') ?>
        </button>
    </div>
    </form>

</div>
