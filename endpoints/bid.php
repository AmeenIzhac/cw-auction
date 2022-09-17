<?php header('Access-Control-Allow-Origin: *'); ?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include("/home/secret.php");
$db = mysqli_connect('localhost',$UN,$PW,'cw-auction-2021')
      or die('Error connecting to MySQL server.');


$ID = $_POST['id'];
$AMOUNT = $_POST['amount'];
$NAME = $_POST['name'];
$EMAIL = $_POST['email'];
$PHONE = $_POST['phone'];
$ITEM_NAME = $_POST['item_name'];

if (!is_numeric($ID)) {
   echo "FAIL";
   die('Invalid ID');
}

if (!is_numeric($AMOUNT)) {
   echo "FAIL";
   die('Invalid Amount');
}


//setup email
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = "smtp";
$mail->SMTPDebug  = 1;  
$mail->SMTPAuth   = TRUE;
$mail->SMTPSecure = "tls";
$mail->Port       = 587;
$mail->Host       = "smtp.gmail.com";
$mail->Username   = "notification.charityweek@gmail.com";
$mail->Password   = $GMPW;



$query = "SELECT amount FROM `max-bid` where id = $ID";


$result = mysqli_query($db, $query) or die('Error querying database.');
$row = mysqli_fetch_array($result);
if (floatval($AMOUNT) > floatval($row['amount'])) {
  $old_email_query = "SELECT email FROM `max-bid` where id = $ID";
  $email_result = mysqli_query($db, $old_email_query) or die('Error querying database.');
  $email_row = mysqli_fetch_array($email_result);
  $old_email = $email_row['email'];
  
  $sql_query = "UPDATE `max-bid` SET `amount`= ?,`name`= ?,`phone`= ?,`email`= ? where id = ?";
  $stmt_query = mysqli_prepare($db, $sql_query);
  mysqli_stmt_bind_param($stmt_query, "dsssi", $AMOUNT, $NAME, $PHONE, $EMAIL, $ID);
  mysqli_stmt_execute($stmt_query);

  $sql_query = "INSERT INTO `bids` (`id`, `name`, `phone`, `email`, `amount`) VALUES (?, ?, ?, ?, ?)";
  $stmt_query = mysqli_prepare($db, $sql_query);
  mysqli_stmt_bind_param($stmt_query, "isssd", $ID, $NAME, $PHONE, $EMAIL, $AMOUNT);
  mysqli_stmt_execute($stmt_query);

  // Send email to previous max bidder
  updateOldBidder($old_email, $AMOUNT, $ITEM_NAME, $mail);

  // Send email to new max bidder
  updateNewBidder($EMAIL, $AMOUNT, $ITEM_NAME, $mail);

} else {
  die("low-bid");
}

function updateOldBidder($old_email, $new_price, $ITEM_NAME, $mail) {
  //
  $mail->IsHTML(true);
  $mail->ClearAddresses();
  $mail->AddAddress($old_email, $old_email);
  $mail->SetFrom("notification.charityweek@gmail.com", "Imperial Charity Week");
  $mail->Subject = "You have been outbid!";
  $content = "<b>You have been outbid on {$ITEM_NAME}. Current bid is at £{$new_price}.</b>";

  $mail->MsgHTML($content); 
  if(!$mail->Send()) {
    echo "Error while sending Email.";
    var_dump($mail);
  } else {
    echo "Email sent successfully";
  }
}

function updateNewBidder($new_email, $new_price, $ITEM_NAME, $mail) {
  $mail->IsHTML(true);
  $mail->ClearAddresses();
  $mail->AddAddress($new_email, $new_email);
  $mail->SetFrom("notification.charityweek@gmail.com", "Imperial Charity Week");
  $mail->Subject = "You are the maximum bidder!";
  $content = "<b>You are the maximum bidder for {$ITEM_NAME}. Current bid is at £{$new_price}.</b>";

  $mail->MsgHTML($content); 
  if(!$mail->Send()) {
    echo "Error while sending Email.";
    var_dump($mail);
  } else {
    echo "Email sent successfully";
  }

}


echo $db->info;
mysqli_close($db);

?>
