<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link  https://www.fromdoppler.com/
 * @since 1.0.0
 *
 * @package    Doppler_For_Woocommerce
 * @subpackage Doppler_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Doppler_For_Woocommerce
 * @subpackage Doppler_For_Woocommerce/public
 * @author     Doppler LLC <info@fromdoppler.com>
 */
class Doppler_For_Woocommerce_Public
{

    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0.0
     * @param string $plugin_name The name of the plugin.
     * @param string $version     The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     * TODO: remove if not necessary.
     *
     * @since 1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/doppler-for-woocommerce-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     * TODO: remove if not necessary.
     *
     * @since 1.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/doppler-for-woocommerce-public.js', array( 'jquery' ), $this->version, false);
        wp_localize_script($this->plugin_name, 'dplrWooAjaxObj', array( 'ajaxurl' => admin_url('admin-ajax.php')));
    }

    public function add_open_graph_meta_tags()
    {
        if (!is_product()) {
            return;
        }

        global $post;
        $product = wc_get_product($post->ID);

        if (!$product) {
            return;
        }

        $title = get_the_title($post);
        $description = get_the_excerpt($post);
        $url = get_permalink($post);
        $image = wp_get_attachment_url($product->get_image_id());
        $price = $product->get_price();
        $currency = get_woocommerce_currency();

        echo '<meta property="og:type" content="product" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url($url) . '" />' . "\n";
        if ($image) {
            echo '<meta property="og:image" content="' . esc_url($image) . '" />' . "\n";
        }
        if ($price) {
            echo '<meta property="product:price:amount" content="' . esc_attr($price) . '" />' . "\n";
            echo '<meta property="product:price:currency" content="' . esc_attr($currency) . '" />' . "\n";
        }
    }
}
