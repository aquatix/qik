<?php

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
		$skel['dict']['loghits'] = 'Bezochte pagina\'s';
		$skel['dict']['logips'] = 'Aantal bezochte pagina\'s per IP adres';
		$skel['dict']['log404s'] = 'Overzicht van 404 fouten';
		$skel['dict']['logpageviews'] = 'Aantal bezochte pagina\'s: %s';
		$skel['dict']['loguniqueips'] = 'Aantal unieke IP adressen: %s';
		$skel['dict']['hostname'] = 'Hostname';
		$skel['dict']['noitemsfound'] = 'Geen items gevonden';

		$skel['dict']['first'] = 'Begin';
		$skel['dict']['previous'] = 'Vorige';
		$skel['dict']['next'] = 'Volgende';
		$skel['dict']['last'] = 'Laatste';

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
		$skel['dict']['loghits'] = 'Visited pages';
		$skel['dict']['logips'] = 'Number of visited pages per IP address';
		$skel['dict']['log404s'] = 'Overview of 404 errors';
		$skel['dict']['logpageviews'] = 'Number of pageviews: %s';
		$skel['dict']['loguniqueips'] = 'Number of unique IP addresses: %s';
		$skel['dict']['hostname'] = 'Hostname';
		$skel['dict']['noitemsfound'] = 'No items found';

		$skel['dict']['first'] = 'First';
		$skel['dict']['previous'] = 'Previous';
		$skel['dict']['next'] = 'Next';
		$skel['dict']['last'] = 'Last';

		$skel['dict']['errormessage'] = 'Error message';
		$skel['dict']['section_not_found'] = 'Section not found';
		$skel['dict']['page_x_not_found'] = 'page "%s" not found';
		$skel['dict']['tile_x_not_found'] = 'ERROR: tile "%s" not found';
		$skel['dict']['newskey_x_not_found'] = 'ERROR: news for "%s" not found';
		$skel['dict']['key_x_not_understood'] = 'ERROR: key "%s" not understood';
		$skel['dict']['gallerykey_x_not_found'] = 'ERROR: gallery "%s" not found';
	}
?>
