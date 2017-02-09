<?php 
require "conn.php";
$event_id = $_POST["event_id"];
$event_type = $_POST["event_type"];
$mysql_qry = "INSERT INTO event (id, type, timestamp) VALUES ('$event_id','$event_type', NOW());";

if($conn->query($mysql_qry) === TRUE) {
echo " Insert success";
}
else {
echo " insert not success";
}
$conn->close; 
?>