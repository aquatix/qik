<?php
require('magpierss/rss_fetch.inc');

function parseRSS($skel, $url, $highlightNewDay = true, $stripTheseTags = null)
{
	$content = '';

	$feed = fetch_rss($url);
	$prevtime = 86401;
	//$prevtime = 0;
	$timeClass = '';

	foreach ($feed->items as $item)
	{
		//if ($highlightNewDay && ((getItemDatestamp($item) % 86400) < $prevtime))
		if ($highlightNewDay && ((getItemDatestamp($item) % 86400) > $prevtime))
		{
			//echo "previous timestamp: " . $prevtime . " this timestamp: " . (getItemDatestamp($item) % 86400) . " without modulo: " . getItemDatestamp($item) . " title: " . $item['title']  ."\n";
			$timeClass = ' style="font-weight: bold" title="' . date('D Y-m-d', getItemDatestamp($item)) . '"';
		} else
		{
			$timeClass = '';
		}
		$description = $item['description'];
		if (null != $stripTheseTags)
		{
			//$description = strip_selected_tags($description, $stripTheseTags);
			$description = strip_tags($description, '<p><ul><li><br>');
			$description = str_replace('<p></p>', '', $description);
		}
		$description = str_replace("\n", "<br />", cleanHTML(trim($description)));
		//$content .= '<td class="time"' . $timeClass . '>' . date('H:i', getItemDatestamp($item)) . '</td><td class="item"><div class="title"><a href="' . $item['link'] . '">' . cleanHTML($item['title']) . '</a></div><div class="desc">' . str_replace("\n", "<br />", cleanHTML(strip_tags(trim($item['description'])))) . "</div></td>\n";
		//$content .= '<td class="time"' . $timeClass . '>' . date('H:i', getItemDatestamp($item)) . '</td><td class="item"><div class="title"><a href="' . $item['link'] . '">' . cleanHTML($item['title']) . '</a></div><div class="desc">' . $description . "</div></td>\n";
		$content .= '<td class="time"' . $timeClass . '>' . date('H:i', getItemDatestamp($item)) . '</td><td class="item"><div class="title"><a href="' . $item['link'] . '">' . $item['title'] . '</a></div><div class="desc">' . $description . "</div></td>\n";
		if ($highlightNewDay)
		{
			$prevtime = getItemDatestamp($item) % 86400;
		}

	}
	return $content;
}

/*** RSS date functions ***/
function getItemDatestamp($item)
{   
	// set default undefined value
	$in_date = ""; 

	// check for RSS 2 as pubdate
	$rss_2_date = $item["pubdate"];

	// check for date defioned in dc:date
	$rss_1_date = $item["dc"]["date"];

	// check for atom date
	$atom_date = $item["issued"];

	//echo 'item: ' . $item['title'] . ' atom: ' . $atom_date . ' rss1: ' . $rss_1_date . ' rss2: ' . $rss_2_date . "\n";

	// convert to appropriate unix time
	if ($atom_date != "") $in_date = parse_w3cdtf($atom_date);
	if ($rss_1_date != "") 
	{   
		if (strlen($rss_1_date) > 10) 
		{   
			//$in_date = parse_w3cdtf($rss_1_date);
			$in_date = strtotime($rss_1_date);
			//echo $in_date . "\n";
		} else
		{   
			// possibly of the form yyyy-mm-dd
			if (ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $rss_1_date, $regs))
			{   
				// calc epoch for current date assuming GMT
				$in_date = gmmktime( 0, 0, 0, $regs[2], $regs[3], $regs[1]);
			}   
		}   
	}   
	if ($rss_2_date != "") $in_date = strtotime($rss_2_date);
	if ($in_date == "") $in_date = time();

	return $in_date;
}   


?>
