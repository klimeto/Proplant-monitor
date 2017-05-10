<?php
$c = pg_connect("host=localhost port=5432 dbname=proplant user=postgres password=only4postgres");

$wantedToPush = 0;
$reallyPushed = 0;

foreach((json_decode($_POST['tbd']))as $key => $val) {
	$wantedToPush++;
	//$spl=preg_split("/\./", $val[7]);
	$decode = json_decode($val);
	$VALS[] = $val;
	$lon = $decode[2];
	$lat = $decode[1];
	$acc = $decode[3];
	if (!$acc)
	{
		$acc = 0;		
	}
	else 
	{
		$acc = "$acc";
	}
	
	$alt = $decode[4];
	if (!$alt)
	{
		$alt = 0;		
	}
	else 
	{
		$alt = "$alt";
	}

	$ala = $decode[5];
	if (!$ala)
	{
		$ala = 0;		
	}
	else 
	{
		$ala = "$ala";
	}
	
	$plnt_name = $decode[10];
	if (!$plnt_name && 0)
	{
		$plnt_name="null";
	}
	else
	{
		$plnt_name="'$plnt_name'";
	}
	$image = $decode[11];
	if (!$image && 0)
	{
		$image="null";
	}
	else
	{
		$image="'$image'";
	}
	$imageDeviceId = "'{$decode[12]}'"; // string
	$obs_date = $decode[15];
	//$spl = preg_split("/#/i", $decode[13]);
	$uid = $decode[16];
	$crtn = $decode[17];
	if (!$crtn && 0)
	{
		$crtn="null";
	}
	else
	{
		$crtn="'$crtn'";
	}
	$site_conf = $decode[18];
	if (!$site_conf && 0)
	{
		$site_conf="null";
	}
	else
	{
		$site_conf="'$site_conf'";
	}
	$site_descr = $decode[19];
	if (!$site_descr && 0)
	{
		$site_descr="null";
	}
	else
	{
		$site_descr="'$site_descr'";
	}
	$imagePathServer = $decode[14];

	if (!$imagePathServer && 0)
	{
		$imagePathServer="null";
	}
	else
	{
		$imagePathServer="'$imagePathServer'";
	}

	$imagePathLocal = $decode[13];
	if (!$imagePathLocal && 0)
	{
		$imagePathLocal="null";
	}
	else
	{
		$imagePathLocal="'$imagePathLocal'";
	}
	
	if(!$obs_date || $obs_date=="null")
	{
		$obs_date = date("Y-m-d");
	}
	
	$q = pg_query($c, "INSERT INTO obs (the_geom, plnt_name, image, obs_date, uid, crtn, site_conf, site_descr, lat, lon, \"imagePathLocal\", \"imagePathServer\",\"imageDeviceId\",acc, alt, ala) VALUES (ST_GeomFromText('MULTIPOINT($lon $lat)',4326),$plnt_name,$image,'$obs_date','$uid',$crtn,$site_conf,$site_descr,'$lat','$lon',$imagePathLocal,$imagePathServer,$imageDeviceId,'$acc','$alt','$ala')");
}

if ($wantedToPush > 0) {
	echo json_encode("insertOK");
	//echo json_encode($VALS);
} else {
	echo json_encode("insertERR");
}

?>