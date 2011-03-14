<?php

/*

Plugin Name: 2Checkout Donate
Plugin URI: http://www.patrickgarman.com/tag/2checkout-donate
Description: This plugin allows site owners to have a donate buttons for visitors to donate via 2Checkout in either set or custom amounts.
Version: 1.0.1
Author: Patrick Garman
Author URI: http://www.patrickgarman.com/
License: GPLv2
*/

/*  Copyright 2011  Patrick Garman  (email : patrickmgarman@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
 
 //https://www.2checkout.com/checkout/purchase
 
//1455660

register_activation_hook(__FILE__, 'twoco_activation');
register_deactivation_hook(__FILE__, 'twoco_deactivation');

	if (is_admin()) {
		add_action( 'admin_menu', 'twoco_admin_menu' );
		add_action( 'admin_init', 'twoco_register_settings' );
	}

function twoco_admin_menu() {
	add_options_page('2CO Donate', '2CO Donate', 'administrator', __FILE__, 'twoco_options_page');
}

function twoco_options_page() {
	echo'
	<div class="wrap">
		<h2>2Checkout Donate Options</h2>
		<form method="post" action="options.php">';
			wp_nonce_field('update-options');
			echo '<table class="form-table">';
				$settings = twoco_settings_list();
				foreach ($settings as $setting) {
					echo '<tr><th scope="row">'.$setting['display'].'</th><td>';
					if ($setting['type']=='radio') {
						echo $setting['yes'].' <input type="'.$setting['type'].'" name="'.$setting['name'].'" value="1" ';
						if (get_option($setting['name'])==1) { echo 'checked="checked" />'; } else { echo ' />'; }
						echo $setting['no'].' <input type="'.$setting['type'].'" name="'.$setting['name'].'" value="0" ';
						if (get_option($setting['name'])==0) { echo 'checked="checked" />'; } else { echo ' />'; }
					} else { echo '<input type="'.$setting['type'].'" name="'.$setting['name'].'" value="'.get_option($setting['name']).'" />'; }
					echo ' (<em>'.$setting['hint'].'</em>)</td></tr>';
				}
			echo '</table>
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="';
			foreach ($settings as $setting) {
				echo $setting['name'].',';
			}
			echo '" /><p class="submit"><input type="submit" class="button-primary" value="Save Changes" /></p>
		</form>';
	echo '</div>';
}

function twoco_settings_list() {
	$settings = array(
		array(
			'display' => '2CO Seller ID',
			'name' => 'twoco_sid',
			'value' => '',
			'type' => 'textbox',
            'hint' => 'your 2CO account number / seller id'
		),
		array(
			'display' => 'Default Donation Amount',
			'name' => 'twoco_amount',
			'value' => '',
			'type' => 'textbox',
            'hint' => 'the default donation amount, WITHOUT currency signs -- ie. 5.00'
		),
		array(
			'display' => 'Force Demo Mode',
			'name' => 'twoco_demo',
			'value' => 0,
			'type' => 'radio',
			'yes' => 'On',
			'no' => 'Off',
            'hint' => 'force all links/buttons to use demo mode'
		),
		

	);
	return $settings;
}

function twoco_register_settings() {
	$settings = twoco_settings_list();
	foreach ($settings as $setting) {
		register_setting($setting['name'], $setting['value']);
	}
}

function twoco_activation() {
	$settings = twoco_settings_list();
	foreach ($settings as $setting) {
		add_option($setting['name'], $setting['value']);
	}
}

function twoco_deactivation() {
	$settings = twoco_settings_list();
	foreach ($settings as $setting) {
		delete_option($setting['name']);
	}
}

function twoco_settings_link($links) {
	$settings_link = '<a href="options-general.php?page=2checkout-donate/2checkout-donate.php">Settings</a>';
	array_unshift($links, $settings_link);
	$support_link = '<a href="https://patrickgarman.zendesk.com/forums/374736-2checkout-donate" target="_blank">Plugin Support</a>';
	array_unshift($links, $support_link);
	return $links;
} $plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'twoco_settings_link' );

// [2checkout]
function twoco_donate_button( $atts, $content = 'Donate' ) {
	extract( shortcode_atts( array(
		'type' => 'button',
		'sid' => get_option('twoco_sid'),
		'amount' => get_option('twoco_amount'),
		'noval' => 0,
	), $atts ) );
	if ($noval==1) { $content .= ' $'.number_format($amount, 2, '.', ','); }
	if ($type=='button') {
		$html='<form action="https://www.2checkout.com/checkout/purchase" method="post">
			<p>
				<input type="hidden" name="sid" value="'.$sid.'"/>
				<input type="hidden" name="cart_order_id" value="12345"/>
				<input type="hidden" name="total" value="'.number_format($amount, 2, '.', ',').'"/>
				<input type="hidden" name="id_type" value="1"/>
				<input type="hidden" name="lang" value="en"/>
				<input type="submit" value="'.$content.'"/>
			</p>
		</form>';
	} else {
		$html='<a href="https://www.2checkout.com/checkout/purchase?
		sid='.$sid.'&amp;
		cart_order_id=12345&amp;
		total='.number_format($amount, 2, '.', ',').'&amp;
		id_type=1">'.$content.'</a>';
	}
	return $html;
}
add_shortcode( '2checkout', 'twoco_donate_button' );
// [/2checkout]