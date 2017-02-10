<?php 
require "conn.php";

$event_type = $_POST["playList"];

if(empty($event_type)) 
{
	echo("No plays selected.");
} 
else
{
	$N = count($event_type);
	echo("You selected $N play(s): ");
	
	for($i=0; $i < $N; $i++){
		$mysql_qry = "INSERT INTO event (id, type, timestamp) VALUES (NULL, $event_type[$i], NOW());";
		if($conn->query($mysql_qry) === TRUE) {
			echo " Insert success";
		}
		else {
			echo " insert not success";
		}
	}
}


$conn->close; 
?>
