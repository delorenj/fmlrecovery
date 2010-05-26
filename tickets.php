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

	default:
			//echo "Unknown Action";
     finalize();
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
    "personName" => $_GET["firstname"]." ".$_GET["lastname"],
    "phoneNumber" => $_GET["phone"],
    "street" => $_GET["street"],
    "city" => $_GET["city"],
    "state" => $_GET["state"],
    "zip" => $_GET["zip"],
    "weight" => $weight_estimate,
    "L" => $length_estimate,
    "W" => $width_estimate,
    "H" => $height_estimate
  );
  $label->init($data);
  $label->create();
  $labelpath = "labels/".User::current_user()->last_name.User::current_user()->first_name.time();
  rename(EpcShippingLabel::SHIP_LABEL, $labelpath.".pdf");
  rename(EpcShippingLabel::SHIP_IMAGE, $labelpath.".png");
  fb("labelpath=$labelpath");
  $ticket = new Ticket(array(
		'user_id'     => User::current_user()->id,
		'service'     => $_SESSION["newticket"]["service"],
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
	if($ticket->is_invalid())
	{
      $result = "1";
      $message = "Error creating ticket!";
	}
	else
	{
		$ticket->save();
      $result = "0";
      $message = "Ticket Created";
   }
  fb($message);
  echo "TEST RESULT";
//  $ar = array("result" => "$result",
//              "message" => "$message");
//
//  echo json_encode($ar);
}

?>
