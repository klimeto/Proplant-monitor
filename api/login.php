<?php
$novy=0;
$spl = preg_split("/#/i", $_GET['user']);
 
$c = pg_connect("host=localhost port=5432 dbname=proplant user=postgres password=only4postgres");
if (!$c) {
  exit;
}

//CASE INSENSITIVE QUERY
$q = pg_query($c, "SELECT * FROM users WHERE \"user\" ILIKE '{$spl[0]}'");

$n = pg_num_rows($q);
if (!$n) 
{
	// INSERT NEW
	$reg_date = date("Y-m-d");
	//$http = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$address = $_SERVER['REMOTE_ADDR'];
	$getcountry = json_decode(file_get_contents("http://ipinfo.io/{$address}/json"));
	$country = $getcountry->country; //
	
	$q = pg_query($c, "INSERT INTO users (\"user\",date,address,country) VALUES ('{$spl[0]}','$reg_date','$address','$country') RETURNING *");
	$novy=1;
}
else 
{
	if (!is_numeric($spl[1]))
	{
		$out['error'] = 'USER EXISTS, ENTER PIN, OR CREATE NEW USER';		
	}
}

$r = pg_fetch_assoc($q);
if(!$novy && $spl[1]!= $r['id']){
	$out['error'] = 'USER EXISTS, PIN IS INCORRECT';
}

		
$out['uid']= $r['id'];
$out['user'] = $r['user'];

echo json_encode($out);
?>