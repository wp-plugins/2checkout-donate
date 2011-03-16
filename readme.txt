=== 2Checkout Donate ===
Contributors: patrickgarman 
Tags: 2co, 2checkout, donate, donation, button, payment,
Requires at least: 3.0.0
Tested up to: 3.1
Stable tag: 1.0.4

This plugin allows site owners to have a donate buttons for visitors to donate via 2Checkout in either set or custom amounts.

== Description ==

This plugin allows site owners to have a donate buttons for visitors to donate via 2Checkout in either set or custom amounts.

To use, simply add the shortcode "2checkout" to your posts or pages. The parameters and some examples are below.

 

type: button|link|manual -- type of element shortcode outputs

sid: custom 2checkout ID -- if you want to enter a custom seller ID...

amount: custom donation amount -- format of 5.00

noval: 0|1 -- set to 1 if you want to add the fixed amount to your text

demo: Y -- if you set demo to Y, transactions will not be processed

target: _self|_target -- HTML target for your element (new window vs same window)

lang: 2checkout language parameter

currency: 2checkout currency parameter

skip_landing: 0|1 -- set to 1 if you want to skip the default landing page during checkout

 

**Usage**

Use all defaults:

[2checkout]



Button that says "Purchase", price $5, in a new window:

[2checkout type="button" amount="5.00" target="_blank"]Purchase[/2checkout]

== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

== Changelog ==
1.0.4: Minor Bug Fixes, Added Parameters, Updated Options Page

1.0.3: Minor Bug Fixes

1.0.2: Added Link Type, Added "amount" Parameter

1.0.1: Minor Bug Fixes

1.0.0: Initial Release