=== Harmonizely Booking Product ===
Contributors: chrishardie, takeittt
Donate link: https://chrishardie.com/refer/donate
Tags: harmonizely, booking, appointment, woocommerce, adopt-me
Requires at least: 5.0
Requires PHP: 7.2
Tested up to: 5.8
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates a Harmonizely appointment booking product type for WooCommerce.

== Description ==

**NO LONGER SUPPORTED**: This plugin is no longer actively maintained or supported, and may be closed soon. [Read the announcement](https://tech.chrishardie.com/2022/ending-support-wordpress-plugins/).

Harmonizely is a service that allows you to connect your calendar and allow people to easily schedule appointments with you. The Harmonizely Booking Product plugin for WordPress and WooCommerce enables selling access to Harmonizely appointment scheduling. You create an appointment booking product in WooCommerce, set the price and choose which Harmonizely meeting type to use. Then, your customers can pay for an appointment and use a personalized, one-time scheduling link to complete the scheduling process.

Here's a short video to show you how it works:

https://www.youtube.com/watch?v=iKKN8snrUGk

This plugin requires a Harmonizely account and API key. If you don't have a Harmonizely account, [sign up for free](https://harmonizely.com?fpr=chris39). (This is an affiliate link; commissions from any resulting purchases will help support this plugin's development.) To get your Harmonizely API key, visit [the Integrations page in your account](https://harmonizely.com/integrations).

= Getting Started =

First, make sure you have WooCommerce installed and configured. Install the plugin and then visit the WooCommerce Product settings, selecting the Harmonizely Bookings section. Enter your Harmonizely API key and save changes. Add a new WooCommerce product and select the "Harmonizely Booking" product type. In the Harmonizely Settings product configuration tab, select the Harmonizely meeting type you want the purchase of this product to grant access to.

When your customers purchase this product, the plugin will retrieve a single-use, personalized scheduling link for your designed meeting type, and store it with the customer order. The order confirmation email will include the scheduling link so they can continue with scheduling. The link is also added to the order notes. Note that only one Harmonizely booking product can be included in a single order. If needed, you can regenerate a new single-use scheduling link using the "Regenerate Harmonizely scheduling link" action from the order edit screen.

= Customization =

You can customize the plugin's behavior in a few ways:

* Adjust the CSS definition for the `harmonizely-booking-notice` and `harmonizely-booking-link` classes in the order confirmation email messages.
* Completely change the order email content by filtering the output of `harmonizely_bookings_order_email_notice`. The filter takes four arguments: the output being filtered, the order object, a boolean indicating whether or not this is a plain text message, and the scheduling URL.

= Contributing =

Feature suggestions, bug reports and pull requests on [GitHub](https://github.com/ChrisHardie/harmonizely-booking-product) are welcome.

= Credit =

Calendar icon courtesy of [srip at Flaticon](https://www.flaticon.com/free-icon/calendar_2693507?term=calendar&page=1&position=3).

== Installation ==

Harmonizely Booking Product is most easily installed via the Plugins tab in your admin dashboard.

== Frequently Asked Questions ==

= How does the customer choose a time and date for the appointment? =

Choosing a time and date happens after the customer has paid for their order and used the single-use scheduling link to visit your Harmonizely scheduling page. This ensures that the customer is always seeing the most up to date calendar information, and so they can take advantage of Harmonizely's ongoing release of new features and interface improvements. It is the store owner's responsibility to make sure the configured Harmonizely meeting type has sufficient availability to satisfy your customers, or to refund the order if an acceptable meeting time cannot be found.

= How do I refresh the list of available meeting types from my Harmonizely account? =

In the WooCommerce Product settings, under the Harmonizely Booking section, re-saving your Harmonizely API key will also refresh the available meeting types. If you are removing a meeting type, make sure you edit any Harmonizely Booking products to update the associated meeting type.

== Screenshots ==

1. Harmonizely product configuration
2. Example booking product during checkout
3. Example appointment scheduling link delivery

== Changelog ==

= 1.0.1 =

* Fix: bug in meeting type drop-down display on product create/configure screen

= 1.0.0 =

* Initial release.
