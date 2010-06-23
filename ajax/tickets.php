<?php
	session_start();
   ob_start();
	require_once('../include/environment.inc');
require_once('fedex/epcshippinglabel.inc');
	include('Mail.php');
	include('Mail/mime.php');
  
	switch($_POST["action"])
	{
		case "create":
			create();
			break;
		case "delete":
			delete();
			break;
		case "finalize":
			finalize();
			break;
    case "testMail":
      sendLabelViaEmail("../labels/DeLorenzoJarad1275680177.pdf", "jaradd@gmail.com");
      break;
    default:
        echo "Unknown Action";
        break;
	}

function delete()
{
  $id = $_POST["id"];
  $t = Ticket::find($id);
  $t->delete();
  echo json_encode(array("OK" => true));
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
  $labelCreatedSuccessfully = rename(dirname(__FILE__)."/../".EpcShippingLabel::SHIP_LABEL, dirname(__FILE__)."/../".$labelpath.".pdf");
  $picCreatedSuccessfully = rename(dirname(__FILE__)."/../".EpcShippingLabel::SHIP_IMAGE, dirname(__FILE__)."/../".$labelpath.".png");
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
$text = User::current_user()->first_name.', your order is being processed.  Attached you will find you pre-paid FedEx shipping label';
$html = '<html>
	  			<body> 
					<div style="padding:0;font-family:arial; font-size:2.4em; color:#00027C"><h1 style="margin:0; padding:0;"><span style="color:#93CD02;">fml</span>Recovery</h1></div>
					<div style="padding-top: -40px; color: #00027C; font-size:1.8em; font-family:arial;">Bringing Data Back To Life</div>
						<p>Dear '.User::current_user()->first_name.', <br /><br />Your order is being processed.  Attached you will find your pre-paid FedEx shipping label.<br />For instructions on how to package your media, please see the links below.</p>
						<div">
						 	<span style="font-weight: 800; color: #93CD02;">fml</span><span style="color:#00027C;">Recovery &trade;</span><br />
							1-973-440-8809<br />
						</div>
					</body>
				 </html>';
$file = $labelpath;
$crlf = "\n";
$hdrs = array('From' => 'tickets@fmlrecovery.com', 'Subject' => 'Your fmlRecovery shipping label: Order #'.User::current_user()->tickets[0]->id);

$mime = new Mail_mime($crlf);

$mime->setTXTBody($text);
$mime->setHTMLBody($html);
$mime->addAttachment($file, 'application/pdf');

//do not ever try to call these lines in reverse order
$body = $mime->get();
$hdrs = $mime->headers($hdrs);

$mail =& Mail::factory('mail');
$mail->send($email, $hdrs, $body);
/*
//define the receiver of the email
$to = $email;
//define the subject of the email
$subject = 'Test email with attachment';
//create a boundary string. It must be unique
//so we use the MD5 algorithm to generate a random hash
$random_hash = md5(date('r', time()));
//define the headers we want passed. Note that they are separated with \r\n
$headers = "From: jarad@fmlrecovery.com\r\nReply-To: info@fmlrecovery.com";
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
//fb($mail_sent ? "Mail sent" : "Mail failed");
 */
}

?>
