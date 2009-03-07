<?php
/**
 * dictionary.php - Qik site framework dictionary file for multilanguage support
 * $Id$
 * Copyright 2005-2009 mbscholt at aquariusoft.org
 *
 * Qik is the legal property of its developer, Michiel Scholten
 * [mbscholt at aquariusoft.org]
 * Please refer to the COPYRIGHT file distributed with this source distribution.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Library General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

	$skel['dict']['language'] = 'en';
	if ($skel['language'] == 'nl')
	{
		$skel['dict']['language'] = 'nl';

		$skel['dict']['404_header'] = 'Pagina niet gevonden';
		$skel['dict']['404'] = 'Fout 404: pagina niet gevonden';
		$skel['dict']['403'] = 'Fout 403: geen toegang tot dit adres';
		$skel['dict']['401'] = 'Fout 401: de juist authenticatiegegevens zijn niet ingegeven. Geen toegang tot dit adres';
		$skel['dict']['http_error'] = 'er is iets mis gegaan';

		$skel['dict']['gohome'] = 'Ga naar de homepage';
		$skel['dict']['gositemap'] = 'Bekijk de sitemap';
		$skel['dict']['sitemap'] = 'Sitemap';
		$skel['dict']['visitslog'] = 'Bezoekerstatistieken';
		$skel['dict']['logpages'] = 'Hits per pagina';
		$skel['dict']['loghitsperdate'] = 'Hits per pagina per dag';
		$skel['dict']['loghits'] = 'Bezochte pagina\'s';
		$skel['dict']['logips'] = 'Aantal bezochte pagina\'s per IP adres';
		$skel['dict']['log404s'] = 'Overzicht van 404 fouten';
		$skel['dict']['logpageviews'] = 'Aantal bezochte pagina\'s: %s';
		$skel['dict']['loguniqueips'] = 'Aantal unieke IP adressen: %s';
		$skel['dict']['hostname'] = 'Hostname';
		$skel['dict']['noitemsfound'] = 'Geen items gevonden';

		$skel['dict']['picture'] = 'Foto';
		$skel['dict']['first'] = 'Begin';
		$skel['dict']['previous'] = 'Vorige';
		$skel['dict']['next'] = 'Volgende';
		$skel['dict']['last'] = 'Laatste';

		$skel['dict']['form_need_valid_email'] = 'Een geldig e-mailadres is noodzakelijk';
		$skel['dict']['form_isrequired_field'] = 'Dit veld is verplicht';
		$skel['dict']['submit'] = 'Verstuur';

		$skel['dict']['errormessage'] = 'Foutmelding';
		$skel['dict']['section_not_found'] = 'Sectie niet gevonden';
		$skel['dict']['page_x_not_found'] = 'pagina "%s" niet gevonden';
		$skel['dict']['tile_x_not_found'] = 'FOUT: tile "%s" niet gevonden';
		$skel['dict']['newskey_x_not_found'] = 'FOUT: nieuws voor sleutel "%s" niet gevonden';
		$skel['dict']['key_x_not_understood'] = 'FOUT: sleutel "%s" niet begrepen';
		$skel['dict']['gallerykey_x_not_found'] = 'FOUT: fotoalbum "%s" niet gevonden';
		//$skel['dict'][''] = '';
	} else
	{
		/* default to en */
		$skel['dict']['404_header'] = 'Page not found';
		$skel['dict']['404'] = 'Error 404: page could not be found';
		$skel['dict']['403'] = 'Error 403: access to this address is forbidden';
		$skel['dict']['401'] = 'Error 401: you didn\'t enter the correct authentication information. Access to this address is forbidden';
		$skel['dict']['http_error'] = 'something went wrong';

		$skel['dict']['gohome'] = 'Go to the homepage';
		$skel['dict']['gositemap'] = 'Take a look at the sitemap';
		$skel['dict']['sitemap'] = 'Sitemap';
		$skel['dict']['visitslog'] = 'Statistics about visitors';
		$skel['dict']['logpages'] = 'Hits per page';
		$skel['dict']['loghitsperdate'] = 'Hits per page per date';
		$skel['dict']['loghits'] = 'Visited pages';
		$skel['dict']['logips'] = 'Number of visited pages per IP address';
		$skel['dict']['log404s'] = 'Overview of 404 errors';
		$skel['dict']['logpageviews'] = 'Number of pageviews: %s';
		$skel['dict']['loguniqueips'] = 'Number of unique IP addresses: %s';
		$skel['dict']['hostname'] = 'Hostname';
		$skel['dict']['noitemsfound'] = 'No items found';

		$skel['dict']['picture'] = 'Picture';
		$skel['dict']['first'] = 'First';
		$skel['dict']['previous'] = 'Previous';
		$skel['dict']['next'] = 'Next';
		$skel['dict']['last'] = 'Last';

		$skel['dict']['form_need_valid_email'] = 'A valid e-mail address is needed';
		$skel['dict']['form_isrequired_field'] = 'This field is required';
		$skel['dict']['submit'] = 'Submit';

		$skel['dict']['errormessage'] = 'Error message';
		$skel['dict']['section_not_found'] = 'Section not found';
		$skel['dict']['page_x_not_found'] = 'page "%s" not found';
		$skel['dict']['tile_x_not_found'] = 'ERROR: tile "%s" not found';
		$skel['dict']['newskey_x_not_found'] = 'ERROR: news for "%s" not found';
		$skel['dict']['key_x_not_understood'] = 'ERROR: key "%s" not understood';
		$skel['dict']['gallerykey_x_not_found'] = 'ERROR: gallery "%s" not found';
	}
?>
