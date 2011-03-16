<?php
/*
Plugin Name: 2Checkout Donate
Plugin URI: http://www.patrickgarman.com/tag/2checkout-donate
Description: This plugin allows site owners to have a donate buttons for visitors to donate via 2Checkout in either set or custom amounts.
Version: 1.0.4
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
	<div class="wrap" style="width:950px;">
		<h2>2Checkout Donate Options</h2>
			<form method="post" action="options.php" style="width:738px; float:left; clear:none;">';
				wp_nonce_field('update-options');
				echo '<table class="form-table">';
				//http://garmanonline.com/ads/garmanonline-728x90.gif
				echo '<tr><td colspan=2><a href="http://www.garmanonline.com" target="_blank"><img src="http://www.garmanonline.com/ads/garmanonline-728x90.gif" alt="GarmanOnline VPS Hosting" /></a></td></tr>';
				$settings = twoco_settings_list();
				foreach ($settings as $setting) {
					echo '<tr><th scope="row">'.$setting['display'].'</th><td>';
					if ($setting['type']=='radio') {
						echo $setting['yes'].' <input type="'.$setting['type'].'" name="'.$setting['name'].'" value="1" ';
						if (get_option($setting['name'])==1) { echo 'checked="checked" />'; } else { echo ' />'; }
						echo $setting['no'].' <input type="'.$setting['type'].'" name="'.$setting['name'].'" value="0" ';
						if (get_option($setting['name'])==0) { echo 'checked="checked" />'; } else { echo ' />'; }
					} elseif ($setting['type']=='select') {
						$values=$setting['values'];
						echo '<select name="'.$setting['name'].'">';
						foreach ($values as $value=>$name) {
							echo '<option value="'.$value.'" ';
							if (get_option($setting['name'])==$value) { echo ' selected="selected" ';}
							echo '>'.$name.'</option>';
						}
						echo '</select>';
					} else { echo '<input type="'.$setting['type'].'" name="'.$setting['name'].'" value="'.get_option($setting['name']).'" />'; }
					echo ' (<em>'.$setting['hint'].'</em>)</td></tr>';
				}
				echo '<tr><th style="text-align:center;"><input type="submit" class="button-primary" value="Save Changes" />';
				echo '<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="';
				foreach ($settings as $setting) {
					echo $setting['name'].',';
				}
				echo '" /></th><td></td></tr></table></form>';
		echo '<form action="http://www.garmanonline.com/2checkout.php" method="get" target="_blank" style="width:200px; float:right; clear:none;">';
			echo '<table class="form-table">';
				echo '<tr><td><h3 style="text-align:center;">Say Thanks to Developers</h3><p>While you may not always be able to donate to developers for the time and effort put into the plugins we create. When you find a plugin that does exactly what you need and works perfectly be sure to at least send over an email to show your appreciation.</p></td></tr>';
				echo '<tr><td style="text-align:center;"><input type="text" value="10.00" name="amount" style="width:60px;" /><input type="submit" value="Buy Patrick Lunch" /></td></tr>';
				echo '<tr><td><a href="https://patrickgarman.zendesk.com/forums/374736-2checkout-donate" target="_blank">Plugin Support Forum</a></td></tr>';
				echo '<tr><td><a href="https://patrickgarman.zendesk.com/anonymous_requests/new" target="_blank">Submit a Support Ticket</a></td></tr>';
				echo '<tr><td><a href="http://www.patrickgarman.com/redirect/2co" target="_blank">Signup for a 2Checkout Account</a></td></tr>';
				echo '<tr><td><a href="http://www.garmanonline.com/" target="_blank">VPS Hosting</a><br /> -- 30% OFF Promo Code: 2COdonate</td></tr>';
				echo '<tr><td><a href="http://profiles.wordpress.org/users/patrickgarman/" target="_blank">View Patrick\'s Other Plugins</a></td></tr>';
			echo '</table>';//http://www.patrickgarman.com/redirect/2co
		echo '</form>';
	echo '</div>';
}

function twoco_settings_list() {
	$settings = array(
		array(
			'display' => '2CO Seller ID',
			'name' => 'twoco_sid',
			'value' => '',
			'type' => 'textbox',
            'hint' => 'your 2CO account number / seller id -- <a target="_blank" href="http://www.patrickgarman.com/redirect/2co">need an account? click here</a>'
		),
		array(
			'display' => 'Default Type',
			'name' => 'twoco_type',
			'value' => 'button',
			'values' => array('button'=>'Button w/Fixed Value','manual'=>'Button w/Text Box','link'=>'Link w/Fixed Value'),
			'type' => 'select',
            'hint' => 'set the default type -- button vs link'
		),
		array(
			'display' => 'Default Donation Amount',
			'name' => 'twoco_amount',
			'value' => '5.00',
			'type' => 'textbox',
            'hint' => 'the default donation amount, WITHOUT currency signs -- ie. 5.00'
		),
		array(
			'display' => 'Default Button/Link Text',
			'name' => 'twoco_content',
			'value' => 'Donate',
			'type' => 'textbox',
            'hint' => 'the default text to be used for buttons or links if none is provided'
		),
		array(
			'display' => 'Single or Multi-Page Checkout',
			'name' => 'twoco_checkout',
			'value' => '_self',
			'values' => array('purchase'=>'Multi-Page Checkout','spurchase'=>'Single Page Checkout'),
			'type' => 'select',
            'hint' => 'single page ONLY supports credit cards'
		),
		array(
			'display' => 'Default Button/Link Target',
			'name' => 'twoco_target',
			'value' => '_self',
			'values' => array('_self'=>'Same Window','_blank'=>'New Window'),
			'type' => 'select',
            'hint' => 'change the default target of where clicking links/buttons takes you to'
		),
		array(
			'display' => 'Default Language',
			'name' => 'twoco_lang',
			'value' => 'en',
			'values' => array(
				'en'=>'English',
				'es_ib'=>'Español (European)',
				'es_la'=>'Español (Latin)',
				'jp'=>'日本語',
				'it'=>'Italiano',
				'nl'=>'Nederlands',
				'pt'=>'Português',
				'el'=>'Ελληνική',
				'sv'=>'Svenska',
				'zh'=>'語言名稱',
				'sl'=>'Slovene',
				'da'=>'Dansk',
				'no'=>'Norsk',
				),
			'type' => 'select',
            'hint' => 'change the default language'
		),
		array(
			'display' => 'Default Currency',
			'name' => 'twoco_currency',
			'value' => 'USD',
			'values' => array(
				'USD'=>'U.S. Dollar',
				'AED'=>'United Arab Emirates Dirham',
				'ARS'=>'Argentina Peso',
				'AUD'=>'Australian Dollar',
				'BGN'=>'Bulgarian Lev',
				'BRL'=>'Brazilian Real',
				'CAD'=>'Canadian Dollar',
				'CHF'=>'Swiss Franc',
				'CLP'=>'Chilean Peso',
				'DKK'=>'Danish Krone',
				'EUR'=>'Euro',
				'GBP'=>'British Pound',                        
				'HKD'=>'Hong Kong Dollar',
				'IDR'=>'Indonesian Rupiah',
				'ILS'=>'Israeli New Shekel',
				'INR'=>'Indian Rupee',
				'JPY'=>'Japanese Yen',
				'LTL'=>'Lithuanian Litas',
				'MXN'=>'Mexican Peso',
				'MYR'=>'Malaysian Ringgit',
				'NOK'=>'Norwegian Krone',               
				'NZD'=>'New Zealand Dollar',
				'PHP'=>'Philippine Peso',
				'RON'=>'Romanian New Leu',
				'RUB'=>'Russian Ruble',
				'SEK'=>'Swedish Krona',
				'SGD'=>'Singapore Dollar',
				'TRY'=>'Turkish Lira',
				'UAH'=>'Ukrainian Hryvnia',
				'ZAR'=>'South African Rand',
				),
			'type' => 'select',
            'hint' => 'change the default currency'
		),
		array(
			'display' => 'Default Skip Landing',
			'name' => 'twoco_skip_landing',
			'value' => 0,
			'type' => 'radio',
			'yes' => 'Skip',
			'no' => 'Show',
            'hint' => 'skip the order review page of the purchase process'
		),
		array(
			'display' => 'Include Amount by Default',
			'name' => 'twoco_noval',
			'value' => 0,
			'type' => 'radio',
			'yes' => 'Yes',
			'no' => 'No',
            'hint' => 'include the amount in the content by default -- ie. Donate vs. Donate $5.00'
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
	$support_link = '<a href="https://patrickgarman.zendesk.com/forums/374736-2checkout-donate" target="_blank">Plugin Support</a>';
	array_unshift($links, $support_link);
	$settings_link = '<a href="options-general.php?page=2checkout-donate/2checkout-donate.php">Settings</a>';
	array_unshift($links, $settings_link);
	return $links;
} $plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'twoco_settings_link' );

// [2checkout]
function twoco_donate_button( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'type' => get_option('twoco_type'),
		'sid' => get_option('twoco_sid'),
		'demo' => get_option('twoco_demo'),
		'target' => get_option('twoco_target'),
		'amount' => get_option('twoco_amount'),
		'noval' => get_option('twoco_noval'),
		'lang' => get_option('twoco_lang'),
		'checkout' => get_option('twoco_checkout'),
		'currency' => get_option('twoco_currency'),
		'skip_landing' => get_option('twoco_skip_landing'),
	), $atts ) );
	if ($content=='') {$content = get_option('twoco_content');}
	if ($noval==1) { $content .= ' $'.number_format($amount, 2, '.', ','); }
	if ($checkout=='single'){$checkout='spurchase';}
	else {$checkout='purchase';}
	if ($type=='button' || $type=='manual') {
		$total_type='hidden'; // lets set the amount field either to hidden
		if($type=='manual'){$total_type='text';} // or a textbox for manual input!
		$html='<form action="https://www.2checkout.com/checkout/'.$checkout.'" method="post" target="'.$target.'">
			<p>
				<input type="hidden" name="sid" value="'.$sid.'"/>
				<input type="'.$total_type.'" name="total" value="'.number_format($amount, 2, '.', ',').'"/>
				<input type="hidden" name="lang" value="'.$lang.'"/>
				<input type="hidden" name="tco_currency" value="'.$currency.'"/>
				<input type="hidden" name="id_type" value="1"/>
				<input type="hidden" name="skip_landing" value="'.$skip_landing.'"/>
				<input type="hidden" name="cart_order_id" value="1"/>';
		 		if ($demo==1) {$html .= '<input type="hidden" name="demo" value="Y"/>';}
				$html .= '<input type="submit" value="'.$content.'"/>';
			$html.='</p></form>';
	} elseif ($type=='link') {
		$html='<a target="'.$target.'" href="https://www.2checkout.com/checkout/'.$checkout.'?';
		$html.='sid='.$sid.'&amp;';
		$html.='total='.number_format($amount, 2, '.', ',').'&amp;';
		$html.='lang='.$lang.'&amp;';
		$html.='tco_currency='.$currency.'&amp;';
		$html.='id_type=1&amp;';
		$html.='skip_landing='.$skip_landing.'&amp;';
		$html.='cart_order_id=1&amp;';
		if ($demo==1) {$html .= 'demo=Y&amp;';}
		$html.='">'.$content.'</a>';
	}
	return $html;
}
add_shortcode( '2checkout', 'twoco_donate_button' );
// [/2checkout]