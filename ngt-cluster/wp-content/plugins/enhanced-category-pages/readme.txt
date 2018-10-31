=== Enhanced Category Pages ===
Contributors: cip, dioneea, danaila_iulian
Tags: categories, taxonomy, term, page, enhanced, custom post, custom post type, category, featured image
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=7K3XA4WQ2BUVJ&lc=US&item_name=Enhanced%20Category%20Wordpress%20Plugin&item_number=Support%20Open%20Source&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Requires at least: 3.0.1
Tested up to: 4.5.3
Stable tag: 2.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create custom enhanced pages for categories and any taxonomy term and manage them as a custom post.

== Description ==

**NEW** Version 2.0.0 brings a great new feature: with some magic, if your theme displays category/term description, then it would be **automatically** enhanced.
Are you ready for more? You can customize the template by creating a `content-ecp.php` file in your theme of choice.


Enhanced Category Pages allows you to create custom category and term pages by managing them using a special custom post type.

**Premium code quality** ensured by Codacy static analysis: [grade A](https://www.codacy.com/app/2wit/enhanced-category-pages).

**Features**

* **NEW** Visual Composer compatible - you can now edit the category using Visual Composer
* **NEW** Genesis Framework compatible
* WooCommerce compatible - product categories can be enhanced now
* Easy to use for everyone: users, designers, developers
* Automatically show enhanced category/term content
* Customize enhanced category/term content by creating a `content-ecp.php` file in your theme of choice
* Traverse categories using setup_ec_data that allows now category id as parameter
* Enhance any taxonomy: edit **any taxonomy** term as a custom post
* Edit category as a custom post - *Enhanced Category*
* Automatically generates *Enhanced Category* post type for each category
* Transparent synchronization of *Enhanced Category* and it's corresponding category
* Add any features available to WordPress custom posts
* Easy *Enhanced Category* display on category template using `<?php $GLOBALS['enhanced_category']->setup_ec_data(); ?>` (see install section)
* Internationalization ready

**Future Features**

* customize *Enhanced Category* custom post type capabilities via plugin options
* manual selection on enhanced categories


== Installation ==
1. Download plugin archive.
2. Upload and uncompress it in "/wp-content/plugins/" directory.
3. Activate the plugin through the "Plugins" menu in WordPress.
4. Use "Enhanced Edit" link to edit the page of the respective category
5. Category/term description display is automatically enhanced with your content.
6. Optional: create `content-ecp.php` in your theme folder to customize the display.

**Advanced usage options**

1. Create `content-ecp.php` in your theme folder to customize the display of the enhanced content. The custom post associated with category/term is set up, so all display functions for posts are usable.


		<?php
			global $enhanced_category;
			// if not previously set up, then let setup_ec_data get the current query term/category
			if (empty($categoryId)) {
				$categoryId = null;
			}

			// get enhanced category post and set it up as global current post
			$enhanced_category->setup_ec_data($categoryId);
		?>

		<!-- enchanced category page (ECP) content -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div>

			<div class="entry-content">
				<?php the_content(); ?>
			</div><!-- .entry-content -->

			<?php edit_post_link( __( 'Edit'), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->' ); ?>

		</article><!-- #post-## -->


1. Display category/term page. Edit **category/taxonomy template** to show the content of the "Enhanced Category" (feel free to adjust to your needs):


        //in category.php or taxonomy.php or any other place your theme displays the category/term content
        <?php
            global $enhanced_category;
            //get enhanced category post and set it up as global current post
            $enhanced_category->setup_ec_data();
        ?>
        <!-- enhanced category content -->
        <?php the_post_thumbnail("medium"); ?>

        <?php get_template_part( 'content', 'page' ); ?>

        <!-- custom fields -->
        <?php
            get_post_custom();
        ?>

        <?php
            // If comments are open or we have at least one comment, load up the comment template
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
        ?>

1. Display a list of categories:


        //$categories is presumed to be an already fetched array of categories/terms
        foreach($categories as $category) {
            $GLOBALS['enhanced_category']->setup_ec_data($category->term_id);
            the_post_thumbnail('thumbnail');
        }


== Frequently Asked Questions ==

= How does magic happen? =

We use the `category_description` or `get_the_archive_description` filters in order to replace the plain content with the enhanced one.

= How can I customize the output? =

* `content-ecp.php` and `content-page.php` partial templates are looked for (in that order) and the first found is loaded.

= What custom post type is created? =

*Enhanced Category* (safe name: enhancedcategory) custom post type is created and a post is generated automatically for each category/term.

= What happens if I edit the category fields? =

*Enhanced Category* Post (ECP) is synchronized in both directions with it's corresponding category i.e. category name - ECP title, category slug - ECP slug, category description - ECP excerpt.

= What happens with *Enhanced Category* posts when the plugin is uninstalled? =

*Enhanced Category* posts are deleted when the plugin is deleted using the WordPress plugin management page. Note: nothing is deleted when the plugin deactivated.

= Can I use it to list any categories/terms? =

Yes, you can pass the category/term id to `setup_ec_data` method like this (`$categories` is presumed to be an already fetched array of categories/terms):
		`
		foreach($categories as $category) {
		    $GLOBALS['enhanced_category']->setup_ec_data($category->term_id);
		    the_post_thumbnail('thumbnail');
		}
		`
= Why do I get a blank screen after installing the plugin? =

*Enhanced Category Post* (ECP) requires at least PHP 5.3 running on your server. Contact your hosting to update the PHP version.



== Screenshots ==
1. Enhanced Edit link in category list
2. Enhanced Edit link in category edit
3. Enhanced Category custom post type edit
4. Category public view

== Changelog ==

= 0.1 =
* Initial release.

= 0.2 =
* Make php 5.3 compatible.

= 1.0 =
* Enhance any taxonomy

= 1.0.1 =
* bug fixing

= 1.0.2 =
* setup_ec_data allows now category id as parameter

= 2.0.0 =
* automatically show the enhanced content using `category_description` or `get_the_archive_description` filters
* customize the display of content with `content-ecp.php` theme partial template

= 2.0.1 =
* bug-fix - prevent undesired PHP warning on category_description filter
* check and update 4.3.1 compatibility

= 2.1.0 =
* WooCommerce product category can be enhanced now

= 2.1.1 =
* Improve code quality

= 2.1.2 =
* Add WordPress 4.4 compatibility

= 2.1.3 =
* Bug fix: correctly handle categories with apostrophes

= 2.2.0 =
* All in One SEO Pack plugin compatibility added

= 2.2.1 =
* Minor bug fix: prevent PHP notice

= 2.3.0 =
* Add Visual Composer compatibility
* Add Genesis Framework compatibility
* Bug fix: Woocommerce shop page does not display the description of the first product anymore
* Bug fix: archive pages were showing the content of the first item

== Upgrade Notice ==

= 0.2 =
* This version adds support for 5.3

= 1.0 =
* Enhance a term from any taxonomy

= 1.0.1 =
* Bugs fixed

= 1.0.2 =
* traverse categories using setup_ec_data that allows now category id as parameter

= 2.0.0 =
* This version adds magic: automatically show the enhanced content using `category_description` or `get_the_archive_description` filters.

= 2.0.1 =
* Bug fixing: prevent undesired PHP warning on category_description filter and update compatibility up to WordPress 4.3.1

= 2.1.0 =
* New feature: WooCommerce product category can be enhanced now

= 2.1.1 =
* Improve code quality

= 2.1.2 =
* Add WordPress 4.4 compatibility

= 2.1.3 =
* Bug fix: correctly handle categories with apostrophes

= 2.2.0 =
* All in One SEO Pack plugin compatibility added

= 2.2.1 =
* Minor bug fix: prevent PHP notice

= 2.3.0 =
* Add Visual Composer compatibility
* Add Genesis Framework compatibility
* Bug fix: Woocommerce shop page does not display the description of the first product anymore
* Bug fix: archive pages were showing the content of the first item
