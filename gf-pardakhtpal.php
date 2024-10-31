<?php
defined( 'ABSPATH' ) OR exit;
/**
 * Plugin Name: Pardakhtpal Gateway For GravityForms
 * Plugin URI: -
 * Description: This plugin lets you use pardakhtpal gateway on gravityforms wp plugin.
 * Version: 1.1
 * Author: Farhan
 * Author URI: wp-src.ir
 * License: GPL2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */


define( 'GF_PARDAKHTPAL_VERSION', '1.0' );

add_action( 'gform_loaded', array( 'GF_PardakhtPal_Bootstrap', 'load' ), 5 );

class GF_PardakhtPal_Bootstrap {

	public static function load(){

		if ( ! method_exists( 'GFForms', 'include_payment_addon_framework' ) ) {
			return;
		}

		require_once( 'class-gf-pardakhtpal.php' );

		GFAddOn::register( 'GFPardakhtPal' );
	}

}

function gf_pardakhtpal(){
	return GFPardakhtPal::get_instance();
}

add_action( 'gform_currencies', 'supported_currencies' );

function supported_currencies( $currencies ) {
    if( ! array_key_exists( 'IRR', $currencies ) )
        $currencies['IRR'] = array( 'name' => 'ریال ایران', 'symbol_left' => 'ریال', 'symbol_right' => '', 'symbol_padding' => ' ', 'thousand_separator' => ',', 'decimal_separator' => '.', 'decimals' => 0 );
    if( ! array_key_exists( 'IRT', $currencies ) )
        $currencies['IRT'] = array( 'name' => 'تومان ایران', 'symbol_left' => 'تومان', 'symbol_right' => '', 'symbol_padding' => ' ', 'thousand_separator' => ',', 'decimal_separator' => '.', 'decimals' => 0 );
    
    return $currencies;
}