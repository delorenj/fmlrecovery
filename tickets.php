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
	$key = $_POST["key"];
  $val = $_POST["val"];
  if(!isset ($_SESSION["newticket"])) { fb("New Ticket Started"); }
  $_SESSION["newticket"][$key] = $val;
  fb($key."=".$_SESSION["newticket"][$key]);
}

?>
