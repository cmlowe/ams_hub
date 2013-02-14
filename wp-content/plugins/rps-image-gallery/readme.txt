=== RPS Image Gallery ===
Contributors: redpixelstudios
Donate link: http://redpixel.com/donate
Tags: gallery, images, slideshow, fancybox
Requires at least: 3.0
Tested up to: 3.5
Stable tag: 1.2.14
License: GPL3

The RPS Image Gallery plugin takes over where the WordPress gallery leaves off by adding slideshow and advanced linking capabilities.

== Description ==

The RPS Image Gallery plugin takes over where the WordPress gallery leaves off by adding slideshow and advanced linking capabilities.

The plugin changes the way the gallery is output by using an unordered list instead of using a definition list for each image. This offers several advantages. There are fewer lines of code per gallery for simplified styling and better efficiency. From an accessibility standpoint, the unordered list is better suited to this type of content than is the definition list. It enables a gallery that will automatically wrap to any given container width.

In addition, any image in the gallery can either invoke a slideshow or link to another page. The link is specified in the Gallery Link field within the image's Edit Media screen as is the link target. When an image that has a Gallery Link is clicked, the user will be directed to that location. Images that link elsewhere are automatically excluded from the slideshow.

There are many other options that allow you to modify the gallery and slideshow output and even a feature that combines attachments from multiple posts or pages into a single gallery.

= Features =
* Compatible with WordPress 3.5 gallery workflow.
* Combine and sort attachments from multiple posts into a single gallery.
* Specify whether clicking an image will invoke a slideshow or link to a page
* Set the target for the image link
* Support for multiple galleries on a single page
* Optionally display the image title and captions in gallery view
* Define sort order of the gallery through the standard familiar interface
* Uses an unordered list instead of a definition list
* Only loads required scripts when shortcode is invoked
* Overrides the default [WordPress Gallery](http://codex.wordpress.org/Gallery_Shortcode "Gallery Shortcode") shortcode but includes all of its options

== Installation ==

1. Upload the <code>rps-image-gallery</code> directory and its containing files to the <code>/wp-content/plugins/</code> directory.
2. Activate the plugin through the "Plugins" menu in WordPress.

== Frequently Asked Questions ==
= How do I add a gallery? =
You can refer to the [gallery instructions](http://en.support.wordpress.com/images/gallery/#adding-a-gallery "Adding a Gallery") posted at WordPress.com support.

= What happens if I deactivate the plugin after having setup galleries with it active? =

Nothing bad. The default [WordPress Gallery](http://codex.wordpress.org/Gallery_Shortcode "Gallery Shortcode") behavior will take over and any shortcode attributes that are specific to the plugin are ignored.

= Where do I set the link and target for each image? =

The fields "Gallery Link URL" and "Gallery Link Target" on the Edit Media screen allow you to specify the settings for each image (see screenshots).

= What attributes are added to the WordPress Gallery shortcode? =

* align (default='left', allowed='left','center','right') - the alignment of the gallery items and the text that appears below them.
* size_large (default='large') - the size of the image that should be displayed in the slideshow view such as 'medium' or 'large'.
* group_name (default='rps-image-group') - the class of the gallery group that is used to determine which images belong to the gallery slideshow.
* container (default='div', allowed='div','p','span') - the overall container for the gallery.
* heading (default='false') - whether or not to display the image title in the gallery and slideshow views.
* headingtag (default='h2') - the tag that should be used to wrap the image heading (title).
* caption (default='false') - whether or not to show the caption under the images in the gallery grid view.
* slideshow (default='true') - whether or not to invoke the slideshow (fancybox) viewer when an image without a Gallery Link value is clicked.

= What attributes of the WordPress Gallery shortcode have been modified? =

* link - By default the only two options are "file" and "permalink". We have added an option of "none" in order to prevent gallery thumbnail images from linking anywhere if slideshow is also set to "false" (since version 1.2.2). An example of this approach is:

<code>
[gallery link=none slideshow=false]
</code>

* id - By default you can use the id to display a gallery that exists on another post/page. We have added the option to pass along a comma delimited list of ids so that a single gallery can be created from multiple galleries. The 'orderby' and 'order' arguments are applied after the attachments are combined. The following example will combine the attachments from post 321 and 455 into a single gallery sorted alphabetically by title:

<code>
[gallery id=321,455 orderby=title order=asc]
</code>

**Notice for WordPress 3.5 Users:** When the "ids" attribute and "id" attribute are present in the same shortcode, the "ids" attribute will be used to determine which images should be included and what order they will be in.

= What attributes are added to the WordPress Gallery shortcode that are specific to the slideshow (fancybox)? =

* fb_title_show (default='true') - whether or not to show the caption under the images in the slideshow view.
* fb_title_position (default='over', allowed='over','outside','inside') - the position of the caption in relation to the image in the slideshow.
* fb_show_close_button (default='true') - whether or not to show the close button in the upper-right corner of the slideshow (clicking outside the slideshow always closes it).
* fb_transition_in (default='none', allowed='none','elastic','fade') - the effect that should be used when the slideshow is opened.
* fb_transition_out (default='none', allowed='none','elastic','fade') - the effect that should be used when the slideshow is closed.
* fb_speed_in (default='300', minimum='100', maximum='1000') - time in milliseconds of the fade and transitions.
* fb_speed_out (default='300', minimum='100', maximum='1000') - time in milliseconds of the fade and transitions.
* fb_title_counter_show (default='true') - whether or not to show the image counter in the slideshow (ie. "Image 1/10).
* fb_cyclic (default='true') - whether or not to loop the slideshow.
* fb_center_on_scroll (default='true') - whether or not to center the image on the screen while scrolling the page.

= Why is the output for the gallery grid set to use an unordered list rather than a definition list? =

The unordered list output is more flexible when used with variable-width layouts since it does not include a break at the end of each row.

= What will display if I set the caption attribute to 'true' but some of my images don't have captions? =

The plugin will fall back to the image title if a caption is not defined for the image.

= Is the image description needed? =

No. We took the approach that the description field should be used to store information about the image that likely would not be seen by the site visitors but could be useful for admins when searching for the image.

= How do I add multiple galleries to the same page? =

Though the WordPress Gallery editor only allows you to manage a single gallery, you can combine galleries from multiple post/pages onto a single page. To do this, create a post/page for each gallery that you want to include. Record the post IDs for the gallery pages, then add a gallery shortcode for each of them on the post/page that will contain them. For example:

<code>
[gallery id=134 group_name=group1]
[gallery id=159 group_name=group2]
</code>

This code will pull the gallery from post 134 and 159 and display them one after the other. The group name attribute allows for each gallery to display in a separate slideshow. Excluding the group name or making it the same will cause the slideshow to be contiguous between the galleries.

Alternatively, you can create multiple galleries from the attached images on a post/page. To do so, get a list of the image (attachment) IDs that you want for each gallery, then pass them to the gallery shortcode in the "include" attribute like so:

<code>
[gallery include=10,11,24,87]
[gallery include=7,16,23,45]
</code>

Keep in mind that all of the included images must be attached to the post/page to be successfully added to the gallery.

= How do I combine multiple galleries? =

Since version 2.0.9, all you need to do to combine multiple galleries is pass along a comma delimited list of ids like so:

<code>
[gallery id=134,159 orderby=title]
</code>

This code will take all of the images from the two galleries, merge and order them by the image title.

= What version of fancybox is being used and are there plans to support fancybox2? =

fancybox version 1.3.4 is included with this plugin and there are plans to support fancybox2 in a future release.

== Screenshots ==

1. Fields named "Gallery Link URL" and "Gallery Link Target" appear on the Edit Media screen for images so that an admin can force the image to link to a post or page on their site or a page on another site.
2. The familiar WordPress Gallery object appears within the Visual editor, just as before the installation of the plugin.
3. The default output for the gallery is the flexible unordered list.
4. Clicking a gallery image opens the slideshow(fancybox) viewer or directs the site visitor to a page specified in the Gallery Link field.

== Changelog ==
= 1.2.14 =
* Unique post gallery images no longer merge into a single slideshow on archive pages.

= 1.2.13 =
* Added pass through arguments for cyclic and centerOnScroll fancybox options.

= 1.2.12 =
* Maintenance release to eliminate warning message being logged when sorting single gallery.

= 1.2.11 =
* Added support for ids attribute in gallery shortcode.
* Reordering merged gallieries is now possible via the default Gallery admin interface.

= 1.2.10 =
* Made column width definitions in CSS more precise for layouts with tight tolerances.

= 1.2.9 =
* Added option to combine attachments from multiple pages into a single gallery while respecting orderby and order arguments.

= 1.2.8 =
* Added classes to indicate beginning and end of gallery rows.
* Added shortcode option to specify gallery alignment.
* Removed definition list styles that were no longer needed.

= 1.2.7 =
* Added title attribute for image in grid view when no link is present.
* Added option to turn image heading (title) on or off in gallery and slideshow views.
* Added option to specify the heading tag from h2-h6.
* Added option to turn the slideshow image counter on or off.
* Removed support for definition list (dl) structure and removed shortcode arguments including itemtag, icontag and captiontag.

= 1.2.6 =
* Added target parameter to gallery link.
* Modified CSS to eliminate extra horizontal space between images in gallery grid due to inline-block styling of list items.

= 1.2.5 =
* Eliminated possibility of HTML markup appearing in title attribute.

= 1.2.4 =
* Added support for HTML markup in the image caption.

= 1.2.3 =
* Modified z-index of fancybox overlay and wrap so that they appear above most theme elements.

= 1.2.2 =
* Corrected an issue with the fancybox CSS that resulted in 404 errors for some supporting graphical elements.
* Added a shortcode attribute option for "link" so that it can now be set to "none".

= 1.2 =
* Added capability to pass fancybox settings through shortcode attributes.
* Changed the default slideshow behavior to be cyclic (loop).
* Corrected an issue preventing slideshow for multiple galleries.

= 1.1.1 =
* First official release version.

== Upgrade Notice ==
= 1.2.14 =
* Improved operation of slideshow when multiple galleries appear on archive pages.

= 1.2.13 =
* Expanded passthrough options for fancybox.

= 1.2.12 =
* Fixed issue that would generate a warning when sorting gallery.

= 1.2.11 =
* Compatibility with WordPress 3.5 ordering and image inclusion standards.

= 1.2.10 =
* Updated default widths for columns.

= 1.2.9 =
* Added option to combine attachments from multiple posts in one gallery.

= 1.2.8 =
* Set default gallery alignment to left with option to override in shortcode.
* Added gallery row classes to allow easier overriding of default margins.

= 1.2.7 =
* Added option to display image title above caption.
* Removed support for definition list (dl) structure.

= 1.2.6 =
* Added support for setting target of gallery link.
* Corrected horizontal image spacing issue in gallery grid view.

= 1.2.5 =
* Fixed bug that allowed HTML markup to appear in title attribute.

= 1.2.4 =
* HTML markup in image captions is now allowed.

= 1.2.3 =
* Fix for users of Twenty Eleven theme and most other themes that display elements overlapping the slideshow.

= 1.2.2 =
* Corrects 404 errors generated by the fancybox CSS when Internet Explorer is the active browser.
* Allow "none" as an option for the link shortcode attribute.

= 1.2 =
* Specify slideshow behavior.
* Corrects an issue whereby only the last gallery on the page could trigger a slideshow.
