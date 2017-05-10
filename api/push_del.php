<?php 
//phpinfo(); exit();
//echo json_encode ($_GET);
//
$c = pg_connect("host=localhost port=5432 dbname=proplant user=postgres password=only4postgres");

$wantedToPush=0;
$reallyPushed=0;


foreach( (json_decode($_POST['tbd'])) as $key=>$val)
{
	$wantedToPush++;
	$q = pg_query($c, "SELECT * FROM obs WHERE \"id\"='$val' AND \"uid\"='{$_POST['user']}'");
	$n = pg_num_rows($q);
	if (!$n) 
	{
		// no valid user or ID found = error which should never happen
		// we just ignore for now
		// TODO: alert user, let him know
		
	}
	else
	{
		$reallyPushed++;
		$r=pg_fetch_assoc($q);
		// delete from DB really
		pg_query($c, "DELETE FROM obs WHERE id='{$r['id']}'");
		// TODO add logs here
	}

}

if($wantedToPush >0){
	echo json_encode("deletedOK");
}
else
{
	echo json_encode("deletedERR");
}

//echo json_encode( Array($wantedToPush, $reallyPushed) );
?>