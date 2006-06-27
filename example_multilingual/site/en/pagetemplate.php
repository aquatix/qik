<?php
/*
 * v0.1.05 2006-06-25
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
	$template .= "<title>" . $page_title . " | Qik example site</title>\n";
	$template .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $skel['base_uri'] . "css/struct.css\"/>\n";
	$template .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $skel['base_uri'] . "css/style.css\"/>\n";
	$template .= "</head>\n<body>\n";

	$template .= "<div class=\"wrapperbox\">\n";
	$template .= "<div class=\"page\">\n";

	/*
	 * simple banner:
	 $template .= "<div id=\"banner\"><a href=\"" . $skel['base_uri'] . "\"><img src=\"" . $skel['base_uri'] . "images/aquariusoft_org.png\" alt=\"aquariusoft.org logo\" width=\"170\" height=\"53\" /></a></div>\n";
	 */
	$template .= "<div id=\"banner\"><span class=\"logo\"><a href=\"" . $skel['base_uri'] . "\"><img src=\"" . $skel['base_uri'] . "images/aquariusoft_org.png\" alt=\"aquariusoft.org\" width=\"170\" height=\"53\" /></a></span><span class=\"portalnav\"><a href=\"http://aquariusoft.org/gallery/\">gallery</a> | <a href=\"http://aquariusoft.org/bugs/\">bugtracker</a> | <a href=\"http://aquariusoft.org/forum/\">forum</a> | <a href=\"http://aquariusoft.org/~mbscholt/\">dammIT weblog</a> | <a href=\"http://jakerockwell.aquariusoft.org/\">jake rockwell</a> | <a href=\"http://www.cs.vu.nl/~mbscholt/\">:M</a> | <a href=\"/page/main/about/\">about</a></span></div>\n";

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
				$result .= '<a href="' . $skel['base_uri'] . getLanguageKey($skel) . 'page/' . $sectionkey . '/"' . $active . '>' . trim($section) . '</a>' . $divider;
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
				$result .= "\t\t\t<li><a href=\"" . $skel['base_uri'] . 'page/' . $section . '/' . $pagekey . '/"' . $active . '>' . trim($page) . "</a></li>\n";
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
?>
