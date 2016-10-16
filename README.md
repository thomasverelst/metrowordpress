# Metro Wordpress Template
http://metro-webdesign.info/wordpress

This template was made during 2014-2015 for Wordpress 3.8, so it might be quite outdated already. Don't use it in a live or production environmnet since I'm not maintaining it since September 2015.

Anyway, I made the code publicly visible since there were many code requests. Since it was just a private hobby project, the code isn't well documented. The layout is a bit retro already (there is a reason why Microsoft changed from horizontal scrolling tiles to vertical scrolling in Win10..), but the code can be quite useful since the AJAX page transitions are pretty unique. 

## Important! Severe bug
There is a severe bug in the tile editor, which sometimes might screw up the tile layout. I didn't find the cause and I suspect it might be due to the gridster.js plugin that's used in the tile editor. But I'm not sure about that. 


## Install
Install as a normal wordpress template (drop the files of this repository in wp-content\themes\metro-template), enable the template. 

Then create a new tile page (look below), and set this page as front page in the Theme Customizer (see below)

Create two new menus: set one as main navigation and the other one as footer navigation. 
## Creating a new page

### Tile page
You can create pages with tiles by adding a new Wordpress page and then setting the page template in the right column ('Page Attributes') to 'Tile page'. Save the page and a GUI should come up where you can add tiles.

### Normal page
To add a normal page, use the "Add new" button, then change your page title and save before (!) starting on the page content. This way the WYSIWYG style is applied on the editor.

In the 'Page Attributes' part you can also change the layout of the page. 

#### Template
- Single post: Shows the date and author on the page, the difference with a real 'post' is that there is no comment section or sidebar.
- Single page: just a normal page
- Archive page: don't use this! Archive pages are pages where you can see multiple blog posts in big tiles, you can't make it manually. 

#### Parent
If the parent is a tile page, the tiles of the parent page will be shown in the background when the page is opened.

## Theme Customizer
The template supports the Wordpress Theme Customizer, which you can acces in your admin menu by going to Appearance > Customize. Here you can change the background image/color, text colors, insert a header image, change the footer...


## Improvements that I should have done
- Fix the tile layout bug
- Use Fontawesome icons instead of images
- Document the code
- Use vertical scrolling instead of the awkwarrd horizontal scrolling (it was based on Windows 8, so...)
- Implement 'featured images'

## Compatible plugins

- The theme should work (at least it did in 2015 ;-) )with SiteOrigin Page Builder, which enables you to create nice page layouts. 
- If your site is quite slow, try WP Super Cache.
