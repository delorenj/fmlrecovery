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
      $result = User::current_user()->addresses;
      $fname = User::current_user()->first_name;
      $lname = User::current_user()->last_name;
      if($result == null){
        $ajaxified_result = array("firstname"=>ucwords($fname),
                                  "lastname"=>ucwords($lname));
        break;

      } else {
        foreach($result as $r){
          if($r->default == "1"){
            $ajaxified_result = array("firstname"=>ucwords($fname),
                                    "lastname"=>ucwords($lname),
                                    "street"=>ucwords($r->streetlines),
                                    "zip"=>$r->postalcode,
                                    "city"=>ucwords($r->city),
                                    "state"=>ucwords($r->stateorprovincecode),
                                    "phone"=>$r->phonenumber);
            break;
          }
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
