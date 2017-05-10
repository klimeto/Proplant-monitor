<?php
$filename = "/var/log/apache2/error.log";
$mode = "r";

$fo = fopen($filename, $mode);
$fileContents = fread($fo, filesize($filename));
fclose($fo);
if(!empty($fileContents)) {
	echo "<pre>".$fileContents."</pre>";
}

?>