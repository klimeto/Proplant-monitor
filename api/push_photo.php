<?php

$c = pg_connect("host=localhost port=5432 dbname=proplant user=postgres password=only4postgres");

echo "VEC START 1:
";
print_r($_POST);
echo "VEC START 2:
";
print_r($_FILES);

$uploaddir = '/var/www/proplant/photo/';
$userPhotoPath = $_POST['myNickname'];
//$spl=preg_split("/\./", $val[7]);
$userPhotoPath = preg_split("/#/", $userPhotoPath);
$userPhotoPath = $userPhotoPath[0];
$userPhotoPath = $uploaddir.$userPhotoPath.'/';

if (!is_dir($userPhotoPath))
{
 mkdir($userPhotoPath,0775);
}

$uploadfile = $userPhotoPath.$_POST['myFilename'].".jpg";


$uploadfilesmall = str_replace(".jpg", "_thumb.jpg", $uploadfile );

/*
var imgW=imageSize.getWidth();
    var imgH=imageSize.getHeight();
	
if(zoomLevel === undefined) { zoomLevel=1.2; }
myWidthHeight=100;
var myWidth = myWidthHeight;
    var myHeight= myWidthHeight;
	
image.width = myWidth*zoomLevel;
image.height= imgH / ( imgW / image.width );

	// this is SET CENTER, ladies and gentlemen..
    image.top  = -((image.height - myHeight)/2);
    image.left = -((image.width -  myWidth) /2);

*/

// Get new sizes
list($width, $height) = getimagesize($_FILES['fileContent']['tmp_name']);

if($width > $height)
{
	// na sirku
	$newwidth = 300;
	$newheight = $height / ( $width / 300 );
}
else
{
	// na vysku
	$newwidth = $width / ( $height / 300 );
	$newheight = 300;
}

// Load
$thumb = imagecreatetruecolor($newwidth, $newheight);
$source = imagecreatefromjpeg($_FILES['fileContent']['tmp_name']);

// Resize
imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

// Output
imagejpeg($thumb, $uploadfilesmall);

//echo '<pre>';
if ($test=move_uploaded_file($_FILES['fileContent']['tmp_name'], $uploadfile)) {
			//echo "File is valid, and was successfully uploaded.\n";
		} else {
			//echo "Possible file upload attack!\n";
}

// DB QUERY 

$imageURL = str_replace('/var/www','http://bolegweb.geof.unizg.hr',$uploadfilesmall);

$q = pg_query($c, "UPDATE obs SET \"imagePathServer\" = '$imageURL' WHERE \"imagePathLocal\"='{$_POST['nativePath']}'");

$out[] = $_FILES['fileContent']['tmp_name'];
$out[]=$test;
file_put_contents($uploaddir."test.txt", "UPDATE obs SET \"imagePathServer\" = '$imageURL' WHERE \"imagePathLocal\"='{$_POST['nativePath']}'");

?>