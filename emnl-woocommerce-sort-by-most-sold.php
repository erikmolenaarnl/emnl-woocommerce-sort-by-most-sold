<?php
/**
 * Plugin Name:       WooCommerce Sort By Most Sold
 * Plugin URI:        https://erikmolenaar.nl
 * Description:       Registers a sorting option in WooCommerce to sort products by the number of times sold, based on the ACF 'emnl_aantal_verkocht' field.
 * Author:            Erik Molenaar
 * Author URI:        https://erikmolenaar.nl
 * Version:           1.0
 * Text Domain:       woocommerce-sort-product-sold
 */

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * @return EMNL_WC_SortProductSold
 */
function emnl_wc_sortproductsold() {
    return EMNL_WC_SortProductSold::get_instance();
}
emnl_wc_sortproductsold();

class EMNL_WC_SortProductSold
{
    /**
	 * @var self
	 */
    private static $_instance = null;

	/**
	 * The option name key.
	 * @var string
	 */
    public $sort_key = 'emnl_aantal_verkocht';

    /**
	 * The order.
	 * @var string
	 */
    public $order = 'DESC';

	/**
	 * EMNL_WC_SortProductSold constructor.
	 */
    protected function __construct() {
        add_filter( 'woocommerce_catalog_orderby', array( $this, 'filter_woocommerce_catalog_orderby' ) );
        add_filter( 'woocommerce_get_catalog_ordering_args', array( $this, 'filter_woocommerce_get_catalog_ordering_args' ), 10, 3 );
    }
    
	/**
	 * Add our custom sorting option.
	 * @param  array $options
	 * @return array
	 */
    public function filter_woocommerce_catalog_orderby( $options ) {
        $options[ $this->sort_key ] = __( 'Sorteren op meest verkocht', 'emnl_aantal_verkocht' );
        return $options;
    }

	/**
	 * WooCommerce order (query) parameters.
	 * @param  array  $args
	 * @param  string $orderby
	 * @param  string $order
	 * @return array
	 */
    public function filter_woocommerce_get_catalog_ordering_args( $args, $orderby, $order ) {
        if ( $this->sort_key === $orderby ) {
            $this->order = ( 'ASC' === $order ) ? 'ASC' : 'DESC';
            $args['meta_key'] = 'emnl_aantal_verkocht';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = $this->order;
        }
        return $args;
    }

	/**
	 * @return self
	 */
    public static function get_instance() {
        if ( ! self::$_instance ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
