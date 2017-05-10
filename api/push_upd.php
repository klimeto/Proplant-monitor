<?php 
$c = pg_connect("host=localhost port=5432 dbname=proplant user=postgres password=only4postgres");


$wantedToPush=0;
$reallyPushed=0;


foreach( (json_decode($_POST['tbd'])) as $key=>$val)
{
	$wantedToPush++;
	//$spl=preg_split("/\./", $val[7]);
	$decode=json_decode($val);
	$kokot = substr($decode[7], strrpos($decode[7], '.') + 1);
	
	$q = pg_query($c, "SELECT * FROM obs WHERE \"id\"='{$kokot}' AND \"uid\"='{$_POST['user']}'");
	$n = pg_num_rows($q);
	if (!$n) 
	{
		
	}
	else
	{
		$reallyPushed++;
		$r=pg_fetch_assoc($q);
		// update DB 
		$vec = "UPDATE obs SET plnt_name='{$decode[10]}', image='{$decode[11]}', \"imagePathLocal\" = '{$decode[13]}', \"imagePathServer\"='{$decode[14]}',\"imageDeviceId\"='{$decode[12]}', obs_date='{$decode[15]}', crtn='{$decode[17]}', site_conf='{$decode[18]}', site_descr='{$decode[19]}' WHERE id='{$r['id']}'";
		//$vec = str_replace("''", "null", $vec);
		pg_query($c, $vec);
		// TODO add logs here
	}
}

if($wantedToPush >0){
	echo json_encode("updatedOK");
}
else
{
	echo json_encode("updatedERR");
}

//echo json_encode($n);

?>