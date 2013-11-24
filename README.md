$Id$

Qik site framework - installation
---------------------------------

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


More information
----------------
For more information, see the Qik page on
http://aquariusoft.org/page/html/qik/
