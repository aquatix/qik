# Qik site framework

Qik is a simple framework to quickly set up a website from scratch. It uses flat files for easy installation, maintenance and versioning.

The current version uses flat files to quickly build a full-fletched website, like the [aquariusoft.org](http://aquariusoft.org) website, or the [website of the Dutch basketball club Mapleleaves](http://mapleleaves.nl). It enables you to build a site from scratch in literally minutes, safe from altering the design. Future releases will feature RSS feeds for news, a management module (<acronym title="Content Management System">CMS</acronym>) and more. No database-based version will be released, as current development focuses on using plain files, which can easily be used with a versioning system like [git](http://git-scm.com), [Mercurial (hg)](http://mercurial.selenic.com) or good oldfashioned [Subversion (svn)](http://subversion.tigris.org).

Written in PHP, it's a fast and easy way to build a site with quite a lot of content. Adding sections and pages is as easy as adding a single line in a simple configuration file, and you don't have to care about the navigation anymore; Qik does that for you. It includes a search function [using Google; if there's sufficient interest in having a build-in search, there'll be one], logging support [including nice overviews], a gallery with automatic creation and caching of thumbnails and quite some other features.


## Installation

Just copy the contents of the tarball to the place you want them to have.
This doesn't have to be the root of your web server. For example:

	tar xjf qik_release.tar.bz2
	mv qik_root/* /var/www/

If you install Qik to be your main website, you can let it handle your Apache
error pages too. For example, add the following to your Apache's config file:

	ErrorDocument 401 /page/error/401/
	ErrorDocument 403 /page/error/403/
	ErrorDocument 404 /page/error/404/
	ErrorDocument 500 /page/error/500/


## Configuring a site

- Copy the contents of the example_site directory to the root of your webserver, for
  example /var/www/
- Done!


## Customizing

Pages, sections, news and tiles are located in the /site subdirectory. If you
add a page to the /site/pages directory, give it a name in the form of
<sectionname>_<pagename>.html , for example home_welcome.html . Add it to the
section file /site/sections/home.desc as "welcome=Welcome at my site" for
example.

There are two CSS (Cascading Style Sheets) files located in the /css directory.
You can modify style.css to your likings. struct.css is for setting up the
general structure of the pages, and can of course be edited too, but that
shouldn't be necessairy.

For this purpose you can also modify /site/pagetemplate.php which contains the
general html frame of the pages, and the code of the navigation and sub
navigation.

You can also create news files in the /site/news directory. For example,
/site/news/home.desc will be used when you put the tag @@@news=home:2:5@@@ in
your page. The 2 and 5 stand for the item it will start and end. You can also
ignore the second number, than it will start at 2 and output all items. If you
only do a @@@news=home@@@ all items will be shown.

Pages can include snippets too. These are called tiles and can be included by
putting a tag of the form @@@tile=todo@@@ in a page. This will include the
contents of /site/tiles/todo.html into your current page. Useful for reusing
certain pieces, for example.


## More information

For more information, see the Qik page on [aquariusoft.org](http://aquariusoft.org/page/html/qik/).
