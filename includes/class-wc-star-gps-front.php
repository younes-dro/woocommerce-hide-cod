<?php

/**
 * 
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('WC_Star_Gps_Front')) {

    class WC_Star_Gps_Front {
        
        public $villes = array();

        public function __construct() {

            add_action('wp_enqueue_scripts', array($this, 'dro_enqueue_script'));
            add_action('wp_enqueue_scripts', array($this, 'getCities'));
            add_filter('woocommerce_checkout_fields', array($this, 'change_city_to_dropdown'));
            add_filter( 'woocommerce_available_payment_gateways', array ( $this, 'payment_gateway_disable_country' ) );            
        }
        
        public function payment_gateway_disable_country( $available_gateways ){

            $listAccpectedCities = array (1, 93 , 94 , 136 , 221 , 215);
            if ( isset( $available_gateways['cod'] ) &&  !in_array ( WC()->customer->get_billing_city() , $listAccpectedCities )  ) {
                unset(  $available_gateways['cod'] );
            }
            
            return $available_gateways;            
        }

        public function change_city_to_dropdown($fields) {
            
            $options = array();
            foreach ( $this->villes->ville as $key => $value) {
                
                $options[] = $value->ville;
                
            }
            sort($options);
            $city_args = wp_parse_args(array(
                'type' => 'select',
                'options' => $options,
                'input_class' => array(
                    'wc-enhanced-select',
                )
                    ), $fields['shipping']['shipping_city']);

            $fields['shipping']['shipping_city'] = $city_args;
            $fields['billing']['billing_city'] = $city_args;

            wc_enqueue_js("
	jQuery( ':input.wc-enhanced-select' ).filter( ':not(.enhanced)' ).each( function() {
		var select2_args = { minimumResultsForSearch: 5 };
		jQuery( this ).select2( select2_args ).addClass( 'enhanced' );
	});");

            return $fields;
        }

        public function getCities() {

            $url = WC_Star_Gps()->plugin_url() . '/data/villes.json';
            $request = wp_remote_get($url);
            if (is_wp_error($request)) {
                var_dump($request);
                return false;
            }
            $body = wp_remote_retrieve_body($request);
            $data = json_decode($body);
            
            return $this->villes = $data;

        }

        public function dro_enqueue_script() {
            
            if ( is_woocommerce()  || is_cart() || is_checkout () ){
                wp_enqueue_style( 'stargps' , WC_Star_Gps()->plugin_url() . '/assets/css/stargps.css' , array() , '20210626' );
            }
            
            return;
        }

    }

}