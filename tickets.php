<?php
	session_start();
	require_once('include/environment.inc');
	switch($_POST["action"])
	{
		case "create":
			create();
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

?>
