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
		case "update":
			update();
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

function update()
{
  $id = $_POST["id"];
  $user = User::find($id);
  $address = Address::first(array('conditions' => array('user_id = ?', $user->id)));
  $user->first_name = $_POST["first_name"];
  $user->last_name = $_POST["last_name"];
  $user->email = $_POST["email"];
  $address->streetlines = $_POST["street"];
  $address->city = $_POST["city"];
  $address->stateorprovincecode = $_POST["state"];
  $address->postalcode = $_POST["zip"];
  $address->phonenumber = $_POST["phone"];
  if($user->is_invalid() || $address->is_invalid()){
    $OK = false;
    if($address->is_invalid()){
      if($address->errors->on('streetlines')) $message .= "* ".$address->errors->on("streetlines")."<br/>";
      if($address->errors->on('city')) $message .= "* ".$address->errors->on("city")."<br/>";
      if($address->errors->on('stateorprovincecode')) $message .= "* ".$address->errors->on("stateorprovincecode")."<br/>";
      if($address->errors->on('postalcode')) $message .= "* ".$address->errors->on("postalcode")."<br/>";
      if($address->errors->on('phonenumber')) $message .= "* ".$address->errors->on("phonenumber")."<br/>";
    }
    if($user->is_invalid()){
      if($user->errors->on('first_name')) $message .= "* ".$user->errors->on("first_name")."<br/>";
      if($user->errors->on('last_name')) $message .= "* ".$user->errors->on("last_name")."<br/>";
      if($user->errors->on('email')) $message .= "* ".$user->errors->on("email")."<br/>";
    }
  }
  else {
    $user->save();
    $address->save();
    $OK = true;
    $message = "Your account info has been updated successfully!";
  }
  echo json_encode(array("OK" => $OK, "message" => $message) );
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