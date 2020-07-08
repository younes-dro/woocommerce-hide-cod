<?php

/**
 * Manages the dependencies that the Plugin needs to operate.
 * 
 * @author    Younes DRO
 * @copyright Copyright (c) 2020, Younes DRO
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Check the compatibility of the environment.
 * 
 * @class WC_Star_Gps
 * @author Younes DRO <younesdro@gmail.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class WC_Star_Gps_Dependencies {

    /** minimum PHP version required by this plugin */
    const MINIMUM_PHP_VERSION = '5.3';

    /** minimum WordPress version required by this plugin */
    const MINIMUM_WP_VERSION = '5.3.2';

    /** minimum WooCommerce version required by this plugin */
    const MINIMUM_WC_VERSION = '3.7.0';
    

    public function __construct() {
        
    }

    /**
     * Checks the PHP version.
     * 
     * @since 1.0.0
     * 
     * @return bool Return true if the PHP version is compatible.Otherwise, will return false.
     */
    public static function check_php_version() {

        return version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '>=' );
    }

    /**
     * Gets the message for display when PHP version is incompatible with this plugin.
     * 
     * @since 1.0.0
     * 
     * @return string Return an informative message.
     */
    public static function get_php_notice() {

        return sprintf(
                esc_html__( 'The minimum PHP version required for this plugin is %1$s. You are running %2$s.', 'stargps')
                , self::MINIMUM_PHP_VERSION, PHP_VERSION );
    }

    /**
     * Checks the WordPress version.
     * 
     * @since 1.0.0
     * 
     * @return bool Return true if the WordPress version is compatible.Otherwise, will return false.
     */
    public static function check_wp_version() {
        
        if ( ! self::MINIMUM_WP_VERSION ){
            return true;
        }

        return version_compare( get_bloginfo( 'version' ), self::MINIMUM_WP_VERSION, '>=' );
    }
    
    /**
     * Gets the message for display when WordPress version is incompatible with this plugin.
     * 
     * @return string Return an informative message.
     */
    public static function get_wp_notice(){
        
        return sprintf(
                esc_html__( '%s is not active, as it requires WordPress version %s or higher. Please %supdate WordPress &raquo;%s', 'stargps')
				,'<strong>' . WC_Star_Gps()->plugin_name . '</strong>',
				self::MINIMUM_WP_VERSION,
				'<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '">', '</a>'
			);
    }
    
    /**
     * Checks WooCommerce is installed, activated and compatible.
     * 
     * @since 1.0.0
     * 
     * @return bool Return true if the WooCommerce is installed , activated and the version is compatible.Otherwise, will return false.
     */
    public static function check_wc_version() {
        
        if ( ! self::MINIMUM_WC_VERSION ){
            return true;
        }
        
        return defined( 'WC_VERSION' ) && version_compare( WC_VERSION, self::MINIMUM_WC_VERSION, '>=' );
    }
    
    /**
     * Gets the message for display when WooCommerce version is not installed , not activated or incompatible with this plugin.
     * 
     * @return string Return an informative message.
     */
    public function get_wc_notice() {
        
        return sprintf(
                esc_html__( '%1$s, as it requires %2$sWooCommerce%3$s version %4$s or higher. Please %5$supdate%6$s or activate WooCommerce ', 'stargps')
				,'<strong>' . WC_Star_Gps()->plugin_name . ' is inactive </strong>',
                                '<a href="' . esc_url ( 'https://wordpress.org/plugins/woocommerce/' ).'">', '</a>',
                                self::MINIMUM_WC_VERSION,
				'<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '">', '</a>'
			);
    }
    


    /**
     * Determines if all the requirements are valid .
     * 
     * @since 1.0.0
     * 
     * @return bool
     */
    public function is_compatible( ) {
     
        return ( self::check_php_version() && self::check_wp_version() );
    }
    
}
