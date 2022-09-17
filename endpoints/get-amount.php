<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
include("/home/secret.php");
$db = mysqli_connect('localhost',$UN,$PW,'cw-auction-2021') 
      or die('Error connecting to MySQL server.');

$query = "SELECT id, amount FROM `max-bid`";

$result = mysqli_query($db, $query) or die('Error querying database.');
mysqli_close($db);

$id = 0;

while ($row = $result->fetch_assoc()) {
   $data->$id->id = $row['id'];
   $data->$id->amount = $row['amount'];
   ++$id;
}

echo json_encode($data);
?>
