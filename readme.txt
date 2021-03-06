=== MP Stacks + Forms ===
Contributors: johnstonphilip
Donate link: http://mintplugins.com/
Tags: message bar, header
Requires at least: 3.5
Tested up to: 4.8
Stable tag: 1.0.0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Put a completely customized form on any page. The form can be set up as a contact email form, or even be used to create WordPress posts for front-end user submission.

== Description ==

Put a completely customized form on any page. The form can be set up as a contact email form, or even be used to create WordPress posts for front-end user submission.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the 'mp-stacks-forms’ folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Build Bricks under the “Stacks and Bricks” menu.
4. Publish your bricks into a “Stack”.
5. Put Stacks on pages using the shortcode or the “Add Stack” button.

== Frequently Asked Questions ==

See full instructions at http://mintplugins.com/doc/mp-stacks

== Screenshots ==


== Changelog ==

= 1.0.0.9 = November 26, 2020
Add label wrapper around fields.

= 1.0.0.8 = August 10, 2017
Add checks in Advanced mode to be sure that the meta key exists before requiring it on the frontend.

= 1.0.0.7 = May 5, 2016
* Vertically align field to top
* Added ability to make fields required
* Added form submission action of "redirect" which allows you to redirect the user to any URL upon form submission.
* Added "Simple" and "Adanced" mode for form. Default to "Simple" Mode which means there are no file uploads or creation of WP posts.
* Simple mode turns off file uploads and confusing options like meta keys. Advanced mode turns those back on but must be manually overridden through the 'mp_stacks_forms_mode' filter

= 1.0.0.6 = December 17, 2015
* Added important to hidden fields css to make sure they stay hidden in themes that explicitly set the display for inputs.
* Changed the submission message to be more subtle.

= 1.0.0.5 = October 28, 2015
* Made forms auto set to make sent emails "reply-to" any email fields used in the form.
* Default field width to 49% instead of 50%. This allows gap for side-by-side fields.

= 1.0.0.4 = September 20, 2015
* Brick Metabox controls now load using ajax.
* Remove Un-needed Admin JS.
* Admin Meta Scripts now enqueued only when needed.
* Front End Scripts now enqueued only when needed.

= 1.0.0.3 = May 1, 2015
* Proper user IP and error checking for array

= 1.0.0.2 = March 9, 2015
* Activation hook installation function added

= 1.0.0.1 = March 9, 2015
* Added security options for max submissions per day and  submission delay.
* Removed unneeded email field as it moved to a repeater

= 1.0.0.0 = March 8, 2015
* Original release
