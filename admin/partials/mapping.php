<?php
if (! current_user_can('manage_options') ) {
    return;
}
?>

<div class="dplr-tab-content">
    <header class="hero-banner">
        <div class="dp-container">
            <div class="dp-rowflex">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <h2><?php _e('Fields Mapping', 'doppler-for-woocommerce')?></h2>
                </div>
                <div class="col-sm-7">
                    <p>
                        <?php _e('Send the information of your Contacts in WooCommerce to Doppler. To do this, select the Doppler Field equivalent to each of the WooCommerce Fields. <br/>Need to create Custom Fields in Doppler?', 'doppler-for-woocommerce'); ?>
                        <a href="<?php _e('https://help.fromdoppler.com/en/how-to-create-a-customized-field?utm_source=landing&utm_medium=integracion&utm_campaign=woocommerce', 'doppler-for-woocommerce')?>" class="green-link"><?php _e('Learn how', 'doppler-for-woocommerce')?></a>.
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
    $maps? $used_fields = array_filter($maps): $used_fields = array();

    if(is_array($wc_fields)) {

        foreach($wc_fields as $fieldtype=>$arr){

            if($fieldtype!='' && $fieldtype!='order' && (count($arr)>0) ) :

                ?>
                <table class="grid panel col-sm-12 col-md-12 col-lg-12 dp-box-shadow">
                    <thead>
                        <tr class="panel-header">
                            <th colspan="2" class="text-white semi-bold">
                                <?php
                                switch($fieldtype){
                                case 'billing':
                                    _e('Billing fields', 'doppler-for-woocommerce');
                                    break;
                                case 'shipping':
                                    _e('Shipping fields', 'doppler-for-woocommerce');
                                    break;
                                case 'account':
                                    _e('Account fields', 'doppler-for-woocommerce');
                                    break;
                                case 'product':
                                    _e('Last purchase fields', 'doppler-for-woocommerce');
                                    break;
                                default:
                                    echo esc_html($fieldtype);
                                    break;
                                }
                                ?>
                            </th>
                        </tr>
                        <tr>
                                <th class="mapping-th-left text-left pt-1 pb-1"><?php _e('WooCommerce Fields', 'doppler-for-woocommerce') ?></th>
                                <th class="text-left pt-1 pb-1"><?php _e('Doppler Fields', 'doppler-for-woocommerce') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                
                <?php

                foreach($arr as $fieldname=>$fieldAtributes){
                    isset($fieldAtributes['type'])? $woo_field_type = $fieldAtributes['type'] : $woo_field_type = 'string';
                    if($fieldname!=='billing_email') :
                        ?>
                            <tr>
                                <td>
                                    <?php 
                                    if($fieldtype==='product') :
                                        _e($fieldAtributes['label'], 'doppler-for-woocommerce');   
                                    else:
                                         echo $fieldAtributes['label'];
                                    endif; ?>
                                </td>
                                <td>
                                    <div class="awa-form">
                                        <div class="dp-select">
                                            <span class="dropdown-arrow"></span>
                                            <select class="dplrwoo-mapping-fields"
                                                name="dplrwoo_mapping[<?php echo $fieldname?>]"
                                                data-type="<?php if (isset($fieldAtributes['type']))
                                                { echo $fieldAtributes['type']; } ?>">
                                                <option></option>
                                                <?php 
                                                foreach ($dplr_fields as $field){
                                                    
                                                    if(($this->check_field_type($woo_field_type, $field->type) && is_array($used_fields) && !in_array($field->name, $used_fields)) || (is_array($maps) && $maps[$fieldname] === $field->name) ) {
                                                        ?>
                                                        <option value="<?php echo esc_attr($field->name)?>" <?php if(is_array($maps) && $maps[$fieldname] === $field->name ) { echo 'selected'; 
                                                                    } ?> data-type="<?php echo esc_attr($field->type) ?>">
                                                            <?php echo esc_html($field->name)?>
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
            <?php _e('Back', 'doppler-for-woocommerce') ?>
        </button>
        </a>

        <button id="dplrwoo-mapping-btn" class="dp-button button-medium primary-green">
            <?php _e('Save and Synchronize', 'doppler-for-woocommerce') ?>
        </button>
    </div>
    </form>

</div>
