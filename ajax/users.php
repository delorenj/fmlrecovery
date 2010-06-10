<?php
	session_start();
	require_once('../include/environment.inc');
	switch($_POST["action"])
	{
		case "get":
			get();
			break;
		case "create":
			create();
			break;
		case "delete":
			delete();
			break;
	default:
			echo "Unknown Action";
	}

function delete()
{
  $uId = $_POST["id"];
  fb("Deleting account: ".$uId);
  $user = User::find($uId);
  if($user->delete()){
    $OK = true;
  }
  else{
    $OK = false;
  }

  echo json_encode(array("OK" => $OK));
}

function create()
{
	$firstName = $_POST["firstname"];
	$lastName = $_POST["lastname"];
	$email = $_POST["email"];
	$password = $_POST["password"];
        $cryptedPassword = crypt($password);
        $street = $_POST["street"];
        $zip = $_POST["zip"];
        $city = $_POST["city"];
        $state = $_POST["state"];
        $phone = $_POST["phone"];
	$response = "CreateAccount|";
	$message = null;
	$user = new User(array(
		'first_name' 				=> $firstName,
		'last_name'  				=> $lastName,
		'email'      				=> $email,
		'crypted_password'                      => $cryptedPassword
	));
	if($user->is_invalid())
	{
		$message = "Error Creating Account!\n";
		$message.= $user->errors->on("email");
		$response.= "1|".$message;
	}
	else
	{
		$user->save();
                $addy = new Address(array(
                        'user_id'         => $user->id,
                        'nickname'        => "default",
                        'streetlines' 		=> $street,
                        'city'  				=> $city,
                        'stateorprovincecode'	=> $state,
                        'postalcode'           	=> $zip,
                        'phonenumber'          	=> $phone
                ));
                if($addy->is_invalid())
                {
                        $message = "Error saving shipping info!\n";
                        $message.= $addy->errors->on("street");
                        $response.= "1|".$message;
                }
                else {
                    $addy->save();
                    $message = "Account Created";
                    $_SESSION['userid'] = $user->id;
                    $response.= "0|".$message."|".$user->first_name;
                }
	}
	echo $response;
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
