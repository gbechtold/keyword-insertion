=== Keyword Insertion for WordPress ===
Contributors: gbechtold, starsmedia
Donate link: https://www.starsmedia.com/donate
Tags: keywords, marketing, url parameters, cornerstone, dynamic content
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.0.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Replace content dynamically based on URL parameters. Perfect for marketing campaigns and personalized content.

== Description ==

Keyword Insertion for WordPress allows you to dynamically replace content on your website based on URL parameters. This is perfect for creating targeted landing pages for different marketing campaigns.

= Features =

* **Dynamic Content**: Replace placeholders with keywords from URL parameters
* **Marketing-Ready**: Create targeted landing pages for different traffic sources
* **Editor Compatible**: Works flawlessly with Cornerstone, Elementor, Gutenberg, and more
* **Secure by Design**: Sanitized inputs and length restrictions to prevent attacks
* **Performance Optimized**: Lightweight code that won't slow down your site
* **Easy to Use**: Simple HTML class-based implementation

= How It Works =

1. Add the `keyword-insert` class to any HTML element where you want the keyword to appear
2. Add the URL parameter (default: 'k') to your links
3. The plugin will automatically replace the content with your keyword

= Use Cases =

* **PPC Landing Pages**: Create one page that dynamically changes based on ad keywords
* **Email Campaigns**: Personalize content based on campaign parameters
* **A/B Testing**: Test different messaging without creating multiple pages

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/keyword-insertion` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings -> Keyword Insertion screen to configure the plugin

== Frequently Asked Questions ==

= Will this work with page builders? =

Yes! This plugin is designed to be compatible with most page builders, including Cornerstone Editor, Elementor, Beaver Builder, Divi Builder, and Gutenberg.

= How do I pass multiple keywords? =

Currently, the plugin supports a single keyword parameter. For advanced use cases, consider using multiple parameters with custom JavaScript.

= Is this compatible with caching plugins? =

Yes, but make sure your caching plugin is configured to exclude URLs with your keyword parameter from the cache.

== Screenshots ==

1. Admin settings page
2. URL preview tool

== Changelog ==

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
Initial release