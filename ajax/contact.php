<?php
	session_start();
	require_once('../include/environment.inc');
	include('Mail.php');
	include('Mail/mime.php');
  
	switch($_POST["action"])
	{
		case "sendMessage":
			sendMessage();
			break;
	default:
			echo "Unknown Action";
	}

function sendMessage()
{
  $name = $_POST["name"];
  $email = $_POST["email"];
  $message = $_POST["message"];

  $text = "From: ".$name."\n\rEmail: ".$email."\n\rMessage: ".$message;
  $html = '<html>
            <body>
            <div style="padding:0;font-family:arial; font-size:2.4em; color:#00027C"><h1 style="margin:0; padding:0;"><span style="color:#93CD02;">fml</span>Recovery</h1></div>
            <div style="padding-top: -40px; color: #00027C; font-size:1.8em; font-family:arial;">Bringing Data Back To Life</div>
              <p>'.$message.'</p>
            </body>
           </html>';
  $crlf = "\n";
  $hdrs = array('From' => $email, 'Subject' => 'fmlRecovery: General Question from Contact Page ('.$name.')');

  $mime = new Mail_mime($crlf);

  $mime->setTXTBody($text);
  $mime->setHTMLBody($html);

  //do not ever try to call these lines in reverse order
  $body = $mime->get();
  $hdrs = $mime->headers($hdrs);

  $mail =& Mail::factory('mail');
  
  if($mail->send("jaradd@gmail.com", $hdrs, $body)) {
    echo "Your message was sent successfully!";
  }
  else {
    echo "Oops, something went wrong. Try again!";
  }

}

?>
