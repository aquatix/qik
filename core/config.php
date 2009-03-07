<?php
/**
 * Config file for Qik site framework
 * Example site, single language
 */

/*
 * set the language to be used by the framework
 * you can choose between en for English and nl for Dutch
 */
$skel['language'] = 'en';
$skel['multilanguage'] = false;

/* Enable or disable Qik's debug mode */
$skel['debugmode'] = false;

/* The title of the site, shown in the browser's titlebar */
$skel['sitetitle'] = 'Qik example site (single language)';

/* Additional information to be shown when a user gets a 'page not found' */
$skel['404_message'] = '<p>For more information about Qik, see <a href="http://aquariusoft.org/page/html/qik/">its page on aquariusoft.org</a></p>';
//$skel['404_message'] .= '<p>Another message.</p>';

/* Set max thumbnail size [optional, defaults to 80px] */
$skel['thumbsize'] = 100;

/* Set usedb to false for using flat files [only supported mechanism at the moment] */
$skel['usedb'] = false;
$skel['dbhost'] = '';
$skel['dbname'] = '';
$skel['dbuser'] = '';
$skel['dbpass'] = '';

$skel['logfile'] = '/var/log/qik/yoursite.log';
$skel['logpass'] = 'password';

/* the text to be displayed in the footer */
$skel['copyright'] = '&copy; 2009 webmaster (at) example.com';
?>
