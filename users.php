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
//      $result = array("firstname"=>"Test","lastname"=>"User", "street"=>"143 Penns Grant Dr", "zip"=>"19067","city"=>"Stanhope", "state"=>"NJ", "phone"=>"2152083549");
      $result = User::current_user()->addresses;
      foreach($result as $r){        
        if($r->default == "1"){
          fb($r,"array dump");
          $ajaxified_result = array("firstname"=>ucwords(User::current_user()->first_name),
                                    "lastname"=>ucwords(User::current_user()->last_name),
                                    "street"=>ucwords($r->streetlines),
                                    "zip"=>$r->postalcode,
                                    "city"=>ucwords($r->city),
                                    "state"=>ucwords($r->stateorprovincecode),
                                    "phone"=>$r->phonenumber);
          break;
        }
      }
      break;
    default:
      $ajaxified_result = "get ".$key." not yet implemented";
      break;
  }
  echo json_encode($ajaxified_result);
}

?>
