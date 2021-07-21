<?php
include('database_connection.php');
session_start();

$data = array(
 ':to_user_id'  => $_POST['to_user_id'],
 ':from_user_id'  => $_SESSION['user_id']
);
$from_user_id = $_SESSION['user_id'];
$to_user_id = $_POST['to_user_id'];
///
// update notifications < - 
	$query2 = "UPDATE chat_message SET status = '0' WHERE from_user_id = '".$to_user_id."' AND to_user_id = '".$from_user_id."' AND status = '1'";
	$statement2 = $connect->prepare($query2);
	$statement2->execute();


// messages 
$query = "SELECT * FROM chat_message WHERE from_user_id = :from_user_id AND to_user_id = :to_user_id OR from_user_id = :to_user_id AND to_user_id = :from_user_id ORDER BY timestamp DESC";

$statement = $connect->prepare($query);
$statement->execute($data);
$result = $statement->fetchAll();

$output = '<ul class="list-unstyled">';
	foreach($result as $row){
		$user_name = '';
	if($row["from_user_id"] == $from_user_id) {
		$user_name = '<b class="text-success">You</b>';
	} else {
		$user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'], $connect).'</b>';
	}
if($row["from_user_id"] == $from_user_id){
$output .= '
  <li style="border-bottom:1px dotted #ccc" align="right">
   '.$user_name.'<font size="2" face="verdana"> '.$row["chat_message"].'
   </font>
   <div align="right">
     - <small><em>'.$row['timestamp'].'</em></small>
    </div>
  </li>
  ';}
else{
	$output .= '
  <li style="border-bottom:1px dotted #ccc" align="left">
   '.$user_name.'<font size="2" face="verdana"> '.$row["chat_message"].'
   </font>
   <div align="left">
     - <small><em>'.$row['timestamp'].'</em></small>
    </div>
  </li>
  ';}
}
	$output .= '</ul>';
	echo $output;
?>
<!--     <div align="right">
     - <small><em>'.$row['timestamp'].'</em></small>
    </div> -->
