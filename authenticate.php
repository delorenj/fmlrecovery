<?php
//session_start();
require_once('FirePHPCore/FirePHP.class.php');
require_once 'php-activerecord/ActiveRecord.php';
ActiveRecord\Config::initialize(function($cfg) {
            $cfg->set_model_directory('models');
            $cfg->set_connections(array('development' => 'mysql://epc:Iree3cam@localhost/epc_development'));
        });
  $firephp = FirePHP::getInstance(true);
	switch($_POST["action"])
	{
		case "login":
			login();
			break;
		case "create-account":
			createAccount();
			break;
		default:
			echo "Unknown Action";
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
    $_SESSION['user'] = $user->email;
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
	$firstName = $_POST["first_name"];
	$lastName = $_POST["last_name"];
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
		$response.= "0|".$message;
	}
	echo $response;
}
?>
