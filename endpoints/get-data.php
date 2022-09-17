<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
include("/home/secret.php");
$db = mysqli_connect('localhost',$UN,$PW,'cw-auction-2021') 
      or die('Error connecting to MySQL server.');


$PASS = $_GET['passphrase'];

if ($PASS != $ADMIN) {
   die('Incorrect passphrase');
}

$query = "SELECT id, amount, name, phone, email, item FROM `max-bid`";

$result = $result = mysqli_query($db, $query) or die('Error querying database.');
mysqli_close($db);

$id = 0;

while ($row = $result->fetch_assoc()) {
   $data->$id->id = $row['id'];
   $data->$id->amount = $row['amount'];
   $data->$id->name = $row['name'];
   $data->$id->phone = $row['phone'];
   $data->$id->email = $row['email'];
   $data->$id->item = $row['item'];
   ++$id;
}

echo json_encode($data);

?>
