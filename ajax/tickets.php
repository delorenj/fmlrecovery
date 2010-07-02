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
//
// This AJAX method is implements the simultaneus lazy-registration and ticket creation for a
// new client and client service, respectively. Both atomic actions must be successful to return an OK result.
//
// TODO: Extrapolate more accurate shipping estimates from the available data in the Ticket model
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
    "mediaSize" => $_SESSION["newticket"]["mediaSize"],
    "mediaType" => $_SESSION["newticket"]["mediaType"],
    "personName" => $_POST["firstname"]." ".$_POST["lastname"],
    "phoneNumber" => $_POST["phone"],
    "email" => User::current_user()->email,
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
  $label->init_email_label($data);
  $label->create_email_label();
  $labelpath = "NONE";
//  $labelpath = "labels/".User::current_user()->last_name.User::current_user()->first_name.time();
//  $labelCreatedSuccessfully = rename(dirname(__FILE__)."/../".EpcShippingLabel::SHIP_LABEL, dirname(__FILE__)."/../".$labelpath.".pdf");
//  $picCreatedSuccessfully = rename(dirname(__FILE__)."/../".EpcShippingLabel::SHIP_IMAGE, dirname(__FILE__)."/../".$labelpath.".png");
//  fb("labelpath=$labelpath");
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
	if($ticket->is_invalid()) // || !$labelCreatedSuccessfully || !$picCreatedSuccessfully)
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
//      sendLabelViaEmail($labelpath.".pdf", User::current_user()->email);
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
						<p>'.User::current_user()->first_name.', <br /><br />Your order is being processed.  Attached you will find your pre-paid FedEx shipping label.<br /></p>
						<div">
						 	<span style="font-weight: 800; color: #93CD02;">fml</span><span style="color:#00027C;">Recovery &trade;</span><br />
							1-973-440-8809<br />
						</div>
					</body>
				 </html>';
$file = $labelpath;
$crlf = "\n";
$hdrs = array('From' => 'tickets@fmlrecovery.com', 'Subject' => 'Your fmlRecovery shipping label: Order #'.end(User::current_user()->tickets)->id);

$mime = new Mail_mime($crlf);

$mime->setTXTBody($text);
$mime->setHTMLBody($html);
$mime->addAttachment($file, 'application/pdf');

//do not ever try to call these lines in reverse order
$body = $mime->get();
$hdrs = $mime->headers($hdrs);

$mail =& Mail::factory('mail');
$mail->send($email, $hdrs, $body);

}

?>
