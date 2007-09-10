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

/* Additional information to be shown when a user gets a 'page not found' */
$skel['404_message'] = '<p>If you are looking for the overload feedreader, please <a href="https://overload.aquariusoft.org">look at overloads new address</a></p>';
//$skel['404_message'] .= '<p>Also, the bugtracker, gallery, forum and webmail and the weblog dammIT will be online again soon.</p>';

/* Set max thumbnail size [optional, defaults to 80px] */
$skel['thumbsize'] = 100;

/* Set usedb to false for using flat files [only supported mechanism at the moment] */
$skel['usedb'] = false;
$skel['dbhost'] = '';
$skel['dbname'] = '';
$skel['dbuser'] = '';
$skel['dbpass'] = '';

$skel['logfile'] = '/var/log/qik/aquariusoftorg.log';
$skel['logpass'] = 'password';

/* the text to be displayed in the footer */
$skel['copyright'] = '&copy; 1999-2007 webmaster (at) aquariusoft.org';
?>
