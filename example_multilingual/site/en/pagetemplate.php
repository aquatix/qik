<?php
/**
 * Page template for Qik site framework, multiple languages
 * $Id$
 */

/*
 * Template for the pages of the website
 */
function buildPage($skel, $page_title, $navbar, $subnavbar, $body)
{
	$template  = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
	$template .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
	$template .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
	//$template .= "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	$template .= "<head>\n";
	$template .= "<title>" . $page_title . ' | ' . $skel['sitetitle'] . "</title>\n";
	$template .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $skel["base_uri"] . "css/style.css\"/>\n";
	$template .= "</head>\n<body>\n";

	$template .= "<div class=\"wrapperbox\">\n";
	$template .= "<div class=\"page\">\n";

	/*
	 * simple banner:
	 $template .= "<div id=\"banner\"><a href=\"" . $skel["base_uri"] . "\"><img src=\"" . $skel["base_uri"] . "images/aquariusoft_org.png\" alt=\"aquariusoft.org logo\" width=\"170\" height=\"53\" /></a></div>\n";
	 */
	$template .= '<div id="banner"><span class="logo"><a href="' . $skel['base_uri'] . '"><img src="' . $skel['base_uri'] . 'images/aquariusoft_org.png" alt="aquariusoft.org" width="170" height="53" /></a></span><span class="portalnav"><a href="http://aquariusoft.org/bugs/">aquariusoft.org bugtracker</a> | <a href="http://aquariusoft.org/forum/">aquariusoft.org forum</a> | <a href="' . $skel['base_uri'] . 'page/' . getLanguageKey($skel) . "main/about/\">about</a></span></div>\n";

	/* Hack for IE's whitespace bug caused by the float */
	$template .= "<br style=\"clear:both; height: 1px;\" />\n";

	$template .= "<div class=\"navbar\">" . $navbar . "</div>\n";

	$template .= "<div class=\"pagebody\">\n";
	$template .= $subnavbar;
	$template .= "\t<div class=\"content\">\n";

	$template .= $body;

	$template .= "\t</div>\n";
	$template .= "<div class=\"footer\">" . $skel["copyright"] . "</div>\n";
	$template .= "<!-- version: " . $skel["version"] . ", parse time: " . (microtime() - $skel["starttime"]) . "sec -->\n";
	$template .= "</div>\n";
	$template .= "</div>\n";
	$template .= "</div>\n";
	$template .= "</body></html>\n";
	return $template;
}


/*
 * Main site navigation
 */
function buildNav($skel, $sections)
{
	$result = '';
	/* If you want a delimiter between the links, add it to $divider */
	$divider = ' ';
	for ($i = 0; $i < count($sections); $i++)
	{
		if ('' != trim($sections[$i]))
		{
			$sectionkey = getKey($sections[$i]);
			$section = getValue($sections[$i]);
			if ('#' != $sectionkey[0])	// '#' denotes a comment in the description file
			{
				/* If you want a delimiter between the links, add it here */
				if (isset($skel['section']) && $sectionkey == $skel['section'])
				{
					$active = ' class="highlight"';
				} else
				{
					$active = '';
				}
				if (count($sections) - 1 == $i)
				{
					$divider = '';
				}
				$result .= '<a href="' . $skel['base_uri'] . 'page/' . getLanguageKey($skel) . $sectionkey . '/"' . $active . '>' . trim($section) . '</a>' . $divider;
			}
		}
	}
	return $result;
}


/*
 * Navigation for the selected section
 */
function buildSubnav($skel, $section, $subsections)
{
	$result = "\t<div class=\"subnavbar\">\n";
	$result .= "\t<h2>" . $skel['sectionname'] . "</h2>\n";
	$result .= "\t\t<ul>\n";
	for ($i = 0; $i < count($subsections); $i++)
	{
		if ('' != trim($subsections[$i]))
		{
			$pagekey = getKey($subsections[$i]);
			$page = getValue($subsections[$i]);
			if ('#' != $pagekey[0] && '' != trim($page))	// '#' denotes a comment in the description file
			{
				if (isset($skel['page']) && $pagekey == $skel['page'])
				{
					$active = ' class="highlight"';
				} else
				{
					$active = '';
				}
				$result .= "\t\t\t<li><a href=\"" . $skel['base_uri'] . 'page/' . getLanguageKey($skel) . $section . '/' . $pagekey . '/"' . $active . '>' . trim($page) . "</a></li>\n";
			}
		}
	}
	$result .= "\t\t</ul>\n";
	$result .= "\t\t<ul class=\"info\">\n";
	$result .= "\t\t\t<li><form action=\"http://www.google.com/search\" method=\"get\"><input type=\"hidden\" name=\"q\" value=\"site:aquariusoft.org\" /><input type=\"text\" class=\"searchfield\" name=\"q\" size=\"20\" /><input type=\"submit\" value=\"find\" /></form></li>\n";
	$result .= "\t\t</ul>\n"; 
	$result .= "\t\t<ul class=\"info\">\n";
	$result .= "\t\t\t<li><a href=\"http://www.mozilla.com/firefox/\" title=\"Get Firefox - Web Browsing Redefined [and take back the web]\"><img src=\"images/firefox_pixel.png\" alt=\"Get Firefox\"/></a></li>\n";
	$result .= "\t\t\t<li><a href=\"http://www.mozilla.com/thunderbird/\" title=\"Get Thunderbird and reclaim your inbox!\"><img src=\"images/thunderbird_pixel.png\" alt=\"Get Thunderbird\"/></a></li>\n";
	$result .= "\t\t</ul>\n";
	$result .= "\t\t<ul class=\"info\">\n";
	$result .= "\t\t\t<li><a href=\"http://aquariusoft.org/page/html/qik/\">build with qik</a></li>\n";
	$result .= "\t\t</ul>\n";
	return $result . "\t</div>\n";
}


/*
 * If site has an admin section, this builds its subnavigation
 */
function buildAdminSubnav($skel, $section, $subsections)
{
	$result = "\t<div class=\"subnavbar\">\n";
	$result .= "\t<h2>" . $skel['sectionname'] . "</h2>\n";
	for ($i = 0; $i < count($subsections); $i++)
	{
		if ('' != trim($subsections[$i]))
		{
			$pagekey = getKey($subsections[$i]);
			$page = getValue($subsections[$i]);
			if ('#' != $pagekey[0] && '' != trim($page))	// '#' denotes a comment in the description file
			{
				if (isset($skel['page']) && $pagekey == $skel['page'])
				{
					$active = ' class="highlight"';
				} else
				{
					$active = '';
				}
				$result .= "<a href=\"" . $skel['base_uri'] . 'admin/' . $section . '/' . $pagekey . '/"' . $active . '>' . trim($page) . "</a> | ";
			}
		}
	}
	return $result . "</div>\n";
}


/*
 * Build a news item (from @@@news@@@ tag) out of $title and $content
 */
function buildNewsItem($skel, $title, $content)
{
	$result .= "<div class=\"news\">\n";
	$result .= "\t<div class=\"date\">" . $title . "</div>";
	$result .= "<div class=\"newscontent\">" . $content . "</div>\n</div>\n\n";
	return $result;
}


/*
 * Build the gallery from the items provided in $galleryitems, which are of the form
 * $galleryitems[$i]['title']
 * $galleryitems[$i]['filename']
 * $galleryitems[$i]['targetfilename']
 */
function buildGallery($skel, $galleryname, $galleryitems)
{
	$result = '';
	$imagecounter = 0;
	for ($i = 0; $i < count($galleryitems); $i++)
	{
		$imagecounter++;
		//$result .= "<div class=\"galleryimage\"><div class=\"theimage\"><a href=\"" . $galleryitems[$i]['targetfilename'] . "\" target=\"_blank\"><img src=\"" . $galleryitems[$i]['filename'] . "\" alt=\"" . $galleryitems[$i]['title'] . "\" /></a></div><p>" . $galleryitems[$i]['title'] . "</p>\n</div>\n";
		$result .= "<div class=\"galleryimage\"><div class=\"theimage\"><a href=\"" . $galleryitems[$i]['galleryitem'] . "\" target=\"_blank\"><img src=\"" . $galleryitems[$i]['filename'] . "\" alt=\"" . $galleryitems[$i]['title'] . "\" /></a></div><p>" . $galleryitems[$i]['title'] . "</p>\n</div>\n";
		/* Insert a horizontal ruler every 4 lines, so unevenly sized items show up correctly */
		/*if ($imagecounter % 4 == 0)
		{
			$result .= "\n<hr />\n";
		}*/
	}
	return $result;
}


/*
 * Build the page in a gallery showing the previous/next thumbnails and the current picture
 */
function buildGalleryPage($skel, $galleryname, $galleryitems, $item)
{
	$item = $item - 1; /* Arrays are 0-based */
        $base = $skel['base_server'] . $skel['base_uri'];

	$body  = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
	$body .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
	$body .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
	$body .= "<head>\n";
	//$body .= "<title>" . $page_title . " | Mapleleaves Heemskerk</title>\n";
	$body .= '<title>' . dict($skel, 'picture') . ': ' . $galleryitems[$item]['title'] . ' | ' . $skel['sitetitle'] . "</title>\n";
	$body .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $skel["base_uri"] . "css/style.css\"/>\n";
        $body .= "<table class=\"gallerynav\" width=\"100%\">\n";
	$previtem = max(0, $item-1);
	$nextitem = min(count($galleryitems)-1, $item+1);
        $body .= '<tr><td height="' . ($skel['thumbsize']+5) . '" width="' . ($skel['thumbsize']+5) . '" style="background-image: url(' . $base . $galleryitems[0]['filename'] . ');"><a href="' . $galleryitems[0]['galleryitem'] . '">|&lt;</a></td><td width="' . ($skel['thumbsize']+5) . '" style="background-image: url(' . $base . $galleryitems[$previtem]['filename'] . ');"><a href="' . $galleryitems[$previtem]['galleryitem'] . '">&lt;&lt;</a></td>';
        //$body .= '<td>Gallery</td>';
        $body .= '<td>' . $galleryitems[$item]['title'] . '</td>';
        $body .= '<td width="' . ($skel['thumbsize']+5) . '" style="background-image: url(' . $base . $galleryitems[$nextitem]['filename'] . ');"><a href="' . $galleryitems[$nextitem]['galleryitem'] . '">&gt;&gt;</a></td><td width="' . ($skel['thumbsize']+5) . '" style="background-image: url(' . $base . $galleryitems[count($galleryitems)-1]['filename'] . ');"><a href="' . $galleryitems[count($galleryitems)-1]['galleryitem'] . '">&gt;|</a></td></tr>';
	$body .= "\n<tr><td colspan=\"5\">";
        $body .= '<img src="' . $galleryitems[$item]['targetfilename'] . '" />';
        $body .= "</td></tr>\n</table>\n";
	$body .= "</body></html>\n";

	return $body;
}
?>
