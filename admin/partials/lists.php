<?php
if ( ! current_user_can( 'manage_options' ) ) {
    return;
}
?>

<div class="dplr-tab-content">
    <header class="hero-banner">
        <div class="dp-container">
            <div class="dp-rowflex">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <h2><?php esc_html_e('Lists to synchronize', 'doppler-for-woocommerce')?></h2>
                </div>
                <div class="col-sm-7">
                    <p>
                        <?php
                        //Check if default lists already exists, set them as selected.
                        $default_buyers_name = __('WooCommerce Buyers','doppler-for-woocommerce');
                        $default_contacts_name = __('WooCommerce Contacts', 'doppler-for-woocommerce');
                        $default_buyers_key = $this->find_list_by_name($default_buyers_name,$lists);
                        $default_contacts_key = $this->find_list_by_name($default_contacts_name,$lists);
                        
                        $suggest_default_lists = false;
                        if( empty($subscribers_lists['contacts']) && empty($subscribers_lists['buyers']) ):
                            $suggest_default_lists = true;
                            esc_html_e('Pick the Doppler Lists you want to import your Users into. You can sync existing Lists or create new ones.', 'doppler-for-woocommerce');
                        else :
                            esc_html_e('As they register to your store or buy a product, your Subscribers will be automatically sent to the selected Doppler Lists.', 'doppler-for-woocommerce');
                        endif;
                        ?>
                    </p>
                </div>
                <div class="col-sm-5 text-align--right">
                    <a href="?page=doppler_woocommerce_menu&tab=fields">
                    <button type="button" class="dp-button button-medium primary-green">
                        <?php esc_html_e('Fields Mapping', 'doppler-for-woocommerce')?>
                    </button>
                    </a>
                </div>
            </div>
            <span class="arrow"></span>
        </div>
    </header>
    <?php $this->display_success_message() ?>

    <?php $this->display_error_message() ?>

    <?php $this->display_warning_message() ?>

    <div id="dplr-settings-text" class="messages-container info d-none">
    </div>

    <div class="col-sm-12 col-md-12 col-lg-12 panel dp-box-shadow p-t-12 p-b-12">
        <form id="dplrwoo-form-list" action="" method="post">
            <?php 
                wp_nonce_field( 'map-lists' );
                /**
                 * If a list is saved in database, select that list. If not
                 * check if default list exists. If default list exists in Doppler, select it,
                 * if not just set it as empty.
                 * This is done to prevent attempting to creatine a default lists that for
                 * some reason already exists in Doppler.
                 */
                $selected_contacts_list = !empty( $subscribers_lists['contacts'])? $subscribers_lists['contacts'] : ( $default_contacts_key ? $default_contacts_key : '') ;
                $selected_buyers_list = !empty( $subscribers_lists['buyers'])? $subscribers_lists['buyers'] : ( $default_buyers_key ? $default_buyers_key : '');
            ?>
            <div class="awa-form">
                <label for="buyers-list">
                    <?php esc_html_e('Doppler List to send Buyers', 'doppler-for-woocommerce')?>
                    <div class="dp-select">
                        <span class="dropdown-arrow"></span>
                        <select name="dplr_subscribers_list[buyers]" class="dplrwoo-lists-sel" id="buyers-list">
                            <option value="0"><?php if($suggest_default_lists && !$default_buyers_key) esc_html_e('WooCommerce Buyers','doppler-for-woocommerce') ?></option>
                            <?php 
                            if(!empty($lists)){
                                foreach($lists as $k=>$v){
                                    if( $selected_contacts_list != $k ):
                                    ?>
                                    <option value="<?php echo esc_attr($k)?>" 
                                        <?php if( $selected_buyers_list == $k  ){ echo 'selected'; $scount = $v['subscribersCount']; } ?>
                                        data-subscriptors="<?php echo esc_attr($v['subscribersCount'])?>">
                                        <?php echo esc_html($v['name'])?>
                                    </option>
                                    <?php
                                    endif;
                                }
                            }   
                            ?>
                        </select>
                    </div>
                </label>
            </div>

            <div class="awa-form m-t-12">
                <label for="contacts-list">
                    <?php esc_html_e('Doppler List to send Contacts', 'doppler-for-woocommerce')?>
                    <div class="dp-select">
                        <span class="dropdown-arrow"></span>
                        <select name="dplr_subscribers_list[contacts]" class="dplrwoo-lists-sel" id="contacts-list">
                            <option value="0"><?php if($suggest_default_lists && !$default_contacts_key) esc_html_e('WooCommerce Contacts', 'doppler-for-woocommerce') ?></option>
                            <?php 
                                if(!empty($lists)){
                                    foreach($lists as $k=>$v){
                                        if( $selected_buyers_list != $k ):
                                        ?>
                                        <option value="<?php echo esc_attr($k)?>" 
                                            <?php if( $selected_contacts_list == $k ){ echo 'selected'; $scount = $v['subscribersCount']; }?>
                                            data-subscriptors="<?php echo esc_attr($v['subscribersCount'])?>">
                                            <?php echo esc_html($v['name']) ?>
                                        </option>
                                        <?php
                                        endif;
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </label>
            </div>
            
            <p class="d-flex justify-end">
                <?php
                $btn_disable = !$suggest_default_lists && ( empty($subscribers_lists['buyers']) && empty($subscribers_lists['contacts']) ) ? 'disabled' : '';
                ?>
            
                <button id="dplrwoo-lists-btn" class="dp-button button-medium primary-green m-t-12" <?php echo esc_attr($btn_disable)?>>
                    <?php esc_html_e('Connect and synchronize', 'doppler-for-woocommerce') ?>
                </button>

            </p>

        </form>
    </div>

    <?php 
    $display_status = get_option('dplrwoo_api_connected');
    if(!empty($display_status)){
        echo '<p><small>' . esc_html__('Connected account', 'doppler-for-woocommerce') . ': ' . esc_html($display_status['account']) . '</small></p>';
    }
    ?>
               
</div>
