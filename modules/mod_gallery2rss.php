<?php
require('magpierss/rss_fetch.inc');

function getGallery2Thumbs($skel, $uri, $nr_items = 5)
{
	$result = "<div class=\"gallery_thumbs\">\n";

	$rss = fetch_rss($uri);

	$counter = 1;
	foreach ($rss->items as $item )
	{
		$result .= '<div class="thumb">' . $item['description'] . "</div\n>";
		$counter++;
		if ($counter > $nr_items) { break; }
	}

	$result .= "</div>\n";
	return $result;
}
?>
