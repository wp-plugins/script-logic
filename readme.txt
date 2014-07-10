=== Script Logic ===
Contributors: tahiryasin
Donate link: NA
Tags: script, style, admin, conditional tags, speed, optimize
Requires at least: 2.8
Tested up to: 3.9.1
Stable tag: 0.3
License: GPLv2 or later

Script Logic lets you control on which pages scripts and style sheets load using WP's conditional tags. 

== Description ==
This plugin lists all JavaScripts and Style sheets with a control field that lets you control CSS & JavaScript files to include only on the pages where you actually need them. The text field lets you use WP's [Conditional Tags](http://codex.wordpress.org/Conditional_Tags), or any general PHP code.

NOTE: The script logic you introduce is evaluated directly. Anyone who has permission to manage options will have the right to add any code, including malicious and possibly destructive code. There is an optional filter 'script_logic_eval_override' which you can use to bypass the EVAL with your own code if needed. (See [Other Notes](other_notes/)).

== Installation ==

1. Upload plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the plugin at `Settings -> Script Logic` 
4. That's it.

= Configuration =

On plugin configuration page you see all script and CSS files with a logic field. Lets suppose you are using Contact Form 7 plugin. You created a page Contact Us (slug = contact-us) and want to include Contact Form 7 scripts only on this page. Find the Contact Form 7 scripts on plugin's configuration page (they have handle = contact-form-7) and put `is_page('contact-us')` in logic field

== Frequently Asked Questions ==

= Why isn't it working? =

Try switching to the WP default theme - if the problem goes away, there is something specific to your theme that may be interfering with the WP conditional tags.

Most probably the logic text on one of your scripts is invalid PHP

= How do I load a certain script X on just my 'home' page? (Or on every page except that.) =

There is some confusion between the [Main Page and the front page](http://codex.wordpress.org/Conditional_Tags#The_Main_Page). If you want a script on your 'front page' whether that is a static page or a set of posts, use is_front_page(). If it is a page using is_page(x) does not work. If your 'front page' is a page and not a series of posts, you can still use is_home() to include scripts on that main posts page (as defined in Admin > Settings > Reading).

== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png

== Writing Logic Code ==

The text in the 'Logic' field can be full PHP code and should return 'true' when you need the script to load. If there is no 'return' in the text, an implicit 'return' is added to the start and a ';' is added on the end. (This is just to make single statements like is_home() more convenient.)

= The Basics =
Make good use of [WP's own conditional tags](http://codex.wordpress.org/Conditional_Tags). You can vary and combine code using:

* `!` (NOT) to **reverse** the logic, eg `!is_home()` is TRUE when this is NOT the home page.
* `||` (OR) to **combine** conditions. `X OR Y` is TRUE when either X is true or Y is true.
* `&&` (AND) to make conditions **more specific**. `X AND Y` is TRUE when both X is true and Y is true.

There are lots of great code examples on the WP forums, and on WP sites across the net. But the WP Codex is also full of good examples to adapt, such as [Test if post is in a descendent category](http://codex.wordpress.org/Template_Tags/in_category#Testing_if_a_post_is_in_a_descendant_category).

= Examples =

*	`is_home()` -- just the main blog page
*	`!is_page('about')` -- everywhere EXCEPT this specific WP 'page'
*	`!is_user_logged_in()` -- shown when a user is not logged in
*	`is_category(array(5,9,10,11))` -- category page of one of the given category IDs
*	`is_single() && in_category('baked-goods')` -- single post that's in the category with this slug
* 	`strpos($_SERVER['HTTP_REFERER'], "google.com")!=false` -- script to load when clicked through from a google search
*	`is_category() && in_array($cat, get_term_children( 5, 'category'))` -- category page that's a descendent of category 5
*	`global $post; return (in_array(77,get_post_ancestors($post)));` -- WP page that is a child of page 77
*	`global $post; return (is_page('home') || ($post->post_parent=="13"));` -- home page OR the page that's a child of page 13

Note the extra ';' on the end where there is an explicit 'return'.

== The 'script_logic_eval_override' filter ==
Before the Script Logic code is evaluated for each script, the text of the Script Logic code is passed through this filter. If the filter returns a BOOLEAN result, this is used instead to determine if the script should load. Return TRUE to load.

== Changelog ==

= 0.1 =
* First stable release.

== Upgrade Notice ==

NA

