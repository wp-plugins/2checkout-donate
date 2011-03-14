=== 2Checkout Donate ===
Contributors: patrickgarman
Donate link: http://www.patrickgarman.com/donate/
Tags: 2checkout, 2co, donate
Requires at least: 3.0.0
Tested up to: 3.1
Stable tag: 1.0.3

This plugin allows site owners to have a donate buttons for visitors to donate via 2Checkout in either set or custom amounts.

== Description ==

This plugin allows site owners to have a donate buttons for visitors to donate via 2Checkout in either set or custom amounts.

To use, simply add the shortcode "2checkout" to your posts or pages. The parameters and some examples are below.

content: Value used for submit button or text used in link.

sid: If you want to enter a custom seller ID...

amount: Custom donation amount.

type: Specify submit button or text link.

noval: Set to 1 if you dont want the value added to the content.

To have a form submit button that says Donate using the default donation amount:

[2checkout]Donate[/2checkout]

To have a link that says Purchase using a custom donation amount without:

[2checkout type=link amount=10.00 noval=1]Purchase[/2checkout]

== Installation ==

1.  Download the latest version of <a href="http://wordpress.org/extend/plugins/2checkout-donate/" target="_blank">2Checkout Donate</a> from the Wordpress Plugin Directory.
2.  Upload the contents of the ZIP file to your /wp-content/plugins directory of your website and activate the plugin.