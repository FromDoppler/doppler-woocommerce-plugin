<?php
if (! defined('ABSPATH') ) { exit; // Exit if accessed directly
}

/*
This class checks if the required dependencies 
*/

class DPLRWOO_Dependecy_Checker
{
    const _DEPENDENCIES = array(
        array('name'=>'WooCommerce', 'repository'=>'https://wordpress.org/plugins/woocommerce', 'plugin_dir' => 'woocommerce/woocommerce.php'),
        array('name'=>'Doppler Forms', 'repository'=>'https://wordpress.org/plugins/doppler-form', 'plugin_dir' => 'doppler-form/doppler-form.php')
    );

    protected $inactive_plugins;

    public function __construct()
    {
        $this->inactive_plugins = array(); 
    }

    public function check()
    {
        $inactive_plugins = array();
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
        foreach(self::_DEPENDENCIES as $plugin):
            if (!in_array($plugin['plugin_dir'], $active_plugins) ) {
                array_push($this->inactive_plugins, $plugin);
            }
        endforeach;
        return count($this->inactive_plugins) === 0;
    }

    public function display_warning()
    {
        if(count($this->inactive_plugins)===0) { return;
        }
        add_action(
            'admin_notices', function () {
                $class = 'notice notice-error';
                $message = __('Ouch! Doppler for WooCommerce will not work if the following plugins are not installed and active:', 'doppler-for-woocommerce');
                $missing_plugins = array();
                $allowed_html = array(
                    'a' => array(
                        'href'   => array(),
                        'target' => array(),
                        'rel'    => array(),
                    ),
                );
                foreach($this->inactive_plugins as $dplrwoo_key=>$plugin){
                    array_push(
                        $missing_plugins,
                        sprintf(
                            '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
                            esc_url($plugin['repository']),
                            esc_html($plugin['name'])
                        )
                    );
                }
                printf(
                    '<div class="%1$s"><p>%2$s %3$s</p></div>',
                    esc_attr($class),
                    esc_html($message),
                    wp_kses(implode(', ', $missing_plugins), $allowed_html)
                );
            }
        );
    }
}
