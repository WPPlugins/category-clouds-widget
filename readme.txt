=== Category Clouds Widget ===
Contributors: hugh.bassett-jones
Tags: category, cloud, widget
Requires at least: 2.8
Tested up to: 3.1
Stable tag: 2.0

Display selected categories as a tag cloud using a sidebar widget or shortcode.

== Description ==
This plugin allows you to add a category cloud widget to your sidebar or use a shortcode to show a tag cloud. You can select the minimum- and maximum- font sizes, the minimum number of posts in a category to show and which catgories to include or exclude.

See [www.bassett-jones.com/category-clouds-wordpress-widget/](http://www.bassett-jones.com/category-clouds-wordpress-widget/) for more details and options.

= Features =

* Use as a widget or shortcode
* Configurable minimum and maximum font sizes
* Configurable font units as pt, px, em or percentage
* Order by number of posts in each category or alphabetically
* Specify the minimum number of posts that a category has to have before it shows
* Specify categories to include or exclude, or use them all

= Usage =
**Shortcode**   
Optionally enter [categoryclouds] in a page or post to show the category cloud. See the FAQ for examples.

**Title**   
This is the usual widget title that will appear in your theme's sidebar.

**Category font size**   
The minimum and maximum font sizes you want the cloud to show and their unit of measurement. For example, min: 50 max: 200 unit: % would show the smallest category at half your normal text size and the largest at double.

**Order by**   
Choose between ordering by number of posts in a category, or alphabetically by category name.

**Show by**   
Either the category with the most posts first or the category with the fewest posts first if using Order by: count, or A-Z or Z-A if Order by: name.

**Minimum number of posts**   
Categories where the total number of posts is less than this number will not be shown. Set to 1 to hide empty categories.

**Comma separated category IDs**   
If you only want to include specific categories, enter their IDs in a list. If you want to exclude a category, enter its ID as a negative number. Leave blank for all categories.

* Example: 1,4,9,36,37,38  
This will create a category cloud with only categories 1,4,9,36,37,38 in it.

* Example: -1,-3   
This will create a category cloud hiding categories 1 and 3.

== Installation ==

Installing the plugin:

1. Download Category Clouds and unzip
2. Upload category_clouds folder to the /wp-content/plugins/ directory
3. Activate the plugin through the ‘Plugins’ menu in WordPress
4. Add the widget to your sidebar through the ‘Appearance > Widgets’ menu

== Frequently Asked Questions ==

= How to I hide empty categories? =

Set the minimum number of posts to 1.

= How do I exclude a category? =

Enter its ID as a negative in the 'Comma separated category IDs' field e.g. to exlude category 5, enter -5

= How do I specify options when using the shortcode? =
Override the following defaults:

* min_size: 50
* max_size: 150
* unit: %
* orderby: name
* order: ASC
* min_count: 1
* cats_inc_exc

Examples

* [categoryclouds]
* [categoryclouds order="DESC"]
* [categoryclouds min_size="8" max_size="24" unit="px"]

== Screenshots ==

1. Example category cloud
2. Widget options

== Changelog ==
= 2.0 =
* Added [categoryclouds] shortcode

= 1.0 = 
* Initial version of the plugin

== Upgrade Notice ==
Version 2 supports [categoryclouds] shortcode.

== Credits ==

This plugin is based on [Category Cloud widget](http://leekelleher.com/wordpress/plugins/category-cloud-widget/) by Lee Kelleher