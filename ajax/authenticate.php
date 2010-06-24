<?php
	session_start();
	require_once('../include/environment.inc');
	switch($_POST["action"])
	{
		case "login":
			login();
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


?>
