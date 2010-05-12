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
    $response.="0|".$message."|".$user->first_name;
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
	$response = "CreateAccount|";
	$message = null;
	$user = new User(array(
		'first_name' 				=> $firstName,
		'last_name'  				=> $lastName,
		'email'      				=> $email,
		'crypted_password' 	=> $cryptedPassword
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
		$message = "Account Created";
    $_SESSION['userid'] = $user->id;
		$response.= "0|".$message."|".$user->first_name;
	}
	echo $response;
}
?>
