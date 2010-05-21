<?php
	session_start();
	require_once('include/environment.inc');
	switch($_POST["action"])
	{
		case "login":
			login();
			break;
		case "create-account":
			createAccount();
			break;
		case "logout":
			logout();
			break;
	default:
			echo "Unknown Action";
	}

function logout()
{
	session_destroy();
}

function login()
{
	$email = $_POST["email"];
	$pw = $_POST["password"];
	$user = User::find_by_email($email);
	$cryptedPassword = $user->crypted_password;
  $response = "Login|";
  $message = null;
	if(crypt($pw, $cryptedPassword) == $cryptedPassword)
	{
    $_SESSION['userid'] = $user->id;
		$message = "Logged In!";
    $response.="0|".$message."|".$user->first_name."|".$user->id;
	}
	else
	{
		$message = "Not Authorized!";
    $response.="1|".$message;
	}
  echo $response;
}		

function createAccount()
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
                        'user_id'                               => $user->id,
                        'nickname'                              => "default",
                        'streetlines' 				=> $street,
                        'city'  				=> $city,
                        'stateorprovincecode'      		=> $state,
                        'postalcode'                     	=> $zip,
                        'phonenumber'                     	=> $phone
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
?>
