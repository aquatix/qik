#!/usr/bin/php
<?php
include '/home/mbscholt/projects/html/qik/src/core/modules/mod_toolkit.php';

$dirpath = "/path/to/your/directory";
$dirpath = $argv[1];

echo 'Creating thumbnails for ' . $dirpath . ":\n";

$dh = opendir($dirpath);
$galleryfile = fopen('/tmp/blah/gallery.desc', 'a');

while (false !== ($file = readdir($dh)))
{
	//Don't list subdirectories
	if (!is_dir("$dirpath/$file"))
	{
		//Truncate the file extension and capitalize the first letter
		echo '- Doing "' . $file . '"... ';
		$path_parts = pathinfo($file);
		$thumbfilename =  '/tmp/blah/' . strtolower($path_parts['basename']);
		$thumbfilename = str_replace(' ', '_', $thumbfilename);
		makeThumbnail($dirpath . $file, $t_ht = 800, $thumbfilename);
		//fwrite($galleryfile, $path_parts['filename'] . '=' . $thumbfilename . "\n");
		fwrite($galleryfile, $file . '=' . $thumbfilename . "\n");
		echo "done\n";
		//makeThumbnail($o_file, $t_ht = 100, $thumbfilename = null)
	}
}
closedir($dh);
?>
