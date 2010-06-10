<?php
	session_start();
   ob_start();
	require_once('../include/environment.inc');

	switch($_POST["action"])
	{
		case "create":
			create();
			break;
    default:
        echo "Unknown Action";
        break;
	}

function create()
{
  $text = $_POST["text"];
  $tId = $_POST["ticketId"];

  fb("Creating comment '$text' for ticket number $tId");
  $comment = new TicketComment(array(
    'ticket_id' => $tId,
    'comment' => $text,
    'admin' => User::is_admin()
  ));

  if($comment->is_invalid()){
    $message = "Oops, that's an invalid comment";
    $OK = false;
  }
  else {
    $comment->save();
    $OK = true;
    $message = "Comment posted";
  }

  $response = array("message" => $message, "OK" => $OK, "commentType" => User::is_admin());
  echo json_encode($response);
}

?>
