<?php
	session_start();
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
			echo "Unknown Action";
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
  $label = new EpcShippingLabel();
  $data = array(
    "personName" => "Radion Khait",
    "phoneNumber" => "9733052261",
    "street" => "150 Parish Dr.",
    "city" => "Wayne",
    "state" => "NJ",
    "zip" => "07470",
    "weight" => 2.0,
    "L" => 5,
    "W" => 2,
    "H" => 5
  );
  $label->init($data);
  $label->create();
  $result = "0";
  $message = "Ticket Created";
  $ar = array("result" => $result,
              "message" => $message);

  echo json_encode($ar);
}

?>
