<?php
	session_start();
   ob_start();
	require_once('include/environment.inc');
   require_once('include/fedex/epcshippinglabel.inc');
  
	switch($_POST["action"])
	{
		case "create":
			create();
			break;
		case "finalize":
			finalize();
			break;
    case "testMail":
      sendLabelViaEmail("This is a test", "jaradd@gmail.com");
      break;
    default:
        echo "Unknown Action";
        break;
	}

function create()
{
  sleep(0);
	$key = $_POST["key"];
  $val = $_POST["val"];
  if(!isset ($_SESSION["newticket"])) { fb("New Ticket Started"); }
  if(!isset ($_SESSION["newticket"][$key])){
    $result = "SUCCESS_INITIAL_SELECTION";
  }
  else{
    $result = "SUCCESS";
  }
  $_SESSION["newticket"][$key] = $val;
  fb($key."=".$_SESSION["newticket"][$key]);
  echo $result;
}

function finalize()
{
  switch($_SESSION["newticket"]["mediaType"]){
    case "external":
      $weight_estimate = 3.0;
      $length_estimate = 5;
      $width_estimate = 2;
      $height_estimate = 5;
      break;
    case "usb":
    case "flash":
    case "phone":
      $weight_estimate = 1.0;
      $length_estimate = 4;
      $width_estimate = 3;
      $height_estimate = 1;
      break;
    default:
      $weight_estimate = 2.0;
      $length_estimate = 5;
      $width_estimate = 2;
      $height_estimate = 5;
      break;
  }
  $label = new EpcShippingLabel();
  $data = array(
    "personName" => $_POST["firstname"]." ".$_POST["lastname"],
    "phoneNumber" => $_POST["phone"],
    "street" => $_POST["street"],
    "city" => $_POST["city"],
    "state" => $_POST["state"],
    "zip" => $_POST["zip"],
    "weight" => $weight_estimate,
    "L" => $length_estimate,
    "W" => $width_estimate,
    "H" => $height_estimate,
    "customerReference" => User::current_user()->id,
    "transactionNumber" => Ticket::last()->id+1
  );
  $label->init($data);
  $label->create();
  $labelpath = "labels/".User::current_user()->last_name.User::current_user()->first_name.time();
  $labelCreatedSuccessfully = rename(EpcShippingLabel::SHIP_LABEL, $labelpath.".pdf");
  $picCreatedSuccessfully = rename(EpcShippingLabel::SHIP_IMAGE, $labelpath.".png");
  fb("labelpath=$labelpath");
  $ticket = new Ticket(array(
		'user_id'     => User::current_user()->id,
		'service_id'     => $_SESSION["newticket"]["service"],
      'media'       => $_SESSION["newticket"]["mediaType"],
		'megabytes'   => $_SESSION["newticket"]["mediaSize"],
      'comments'    => $_SESSION["newticket"]["mediaDetail"],
		'weight'      => $weight_estimate,
      'length'      => $length_estimate,
      'width'       => $width_estimate,
      'height'      => $height_estimate,
      'shipping_cost' => "10.00",
      'labelpath'     => $labelpath
	));
	if($ticket->is_invalid() || !$labelCreatedSuccessfully || !$picCreatedSuccessfully)
	{
    $result = "FAILURE";
    $message = "Error creating ticket";
    if(!$labelCreatedSuccessfully || !$picCreatedSuccessfully) {
      $message.=": Looks like FedEx Web Services are currently down. Try again later.";
    }
	}
	else
	{
		$ticket->save();
      $result = "OK";
      $message = "Ticket Created";
      sendLabelViaEmail($labelpath.".pdf", User::current_user()->email);
   }
  fb($message);  
  $ar = array("result" => $result,
              "message" => $message,
              "labelpath" => $labelpath);

  echo json_encode($ar);
}

function sendLabelViaEmail($labelpath, $email)
{
  mail( "jaradd@gmail.com", "Test Email", $labelpath, "From: test@etherealPC.com" );

}
/*
//define the receiver of the email
$to = 'jaradd@gmail.com';
//define the subject of the email
$subject = 'Test email with attachment';
//create a boundary string. It must be unique
//so we use the MD5 algorithm to generate a random hash
$random_hash = md5(date('r', time()));
//define the headers we want passed. Note that they are separated with \r\n
$headers = "From: admin@nardis.delonet\r\nReply-To: admin@nardis.delonet";
//add boundary string and mime type specification
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";
//read the atachment file contents into a string,
//encode it with MIME base64,
//and split it into smaller chunks
$attachment = chunk_split(base64_encode(file_get_contents($labelpath)));
//define the body of the message.
ob_start(); //Turn on output buffering
?>
--PHP-mixed-<?php fb($random_hash); ?>
Content-Type: multipart/alternative; boundary="PHP-alt-<?php fb($random_hash); ?>"

--PHP-alt-<?php fb($random_hash); ?>
Content-Type: text/plain; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

Hello World!!!
This is simple text email message.

--PHP-alt-<?php fb($random_hash); ?>
Content-Type: text/html; charset="iso-8859-1"
Content-Transfer-Encoding: 7bit

<h2>Hello World!</h2>
<p>This is something with <b>HTML</b> formatting.</p>

--PHP-alt-<?php fb($random_hash); ?>--

--PHP-mixed-<?php fb($random_hash); ?>
Content-Type: application/zip; name="attachment.zip"
Content-Transfer-Encoding: base64
Content-Disposition: attachment

<?php fb($attachment); ?>
--PHP-mixed-<?php fb($random_hash); ?>--

<?php
//copy current buffer contents into $message variable and delete current output buffer
$message = ob_get_clean();
//send the email
$mail_sent = @mail( $to, $subject, $message, $headers );
//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed"
fb($mail_sent ? "Mail sent" : "Mail failed");
}
*/
?>