<?php
	session_start();
	require_once('include/environment.inc');
	switch($_POST["action"])
	{
		case "get":
			get();
			break;
	default:
			echo "Unknown Action";
	}

function get()
{
  sleep(0);
  $uid = User::current_user()->id;
  if($uid == NULL){
    return false;
  }

  $key = $_POST["key"];

  switch($key){
    case "defaultAddress":
      $result = array("firstname"=>"Test","lastname"=>"User", "street"=>"143 Penns Grant Dr", "zip"=>"19067","city"=>"Stanhope", "state"=>"NJ", "phone"=>"2152083549");
      break;
    default:
      $result = "get ".$key." not yet implemented";
      break;
  }
  echo json_encode($result);
}

?>
