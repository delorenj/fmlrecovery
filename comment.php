<?php
	session_start();
   ob_start();
	require_once('include/environment.inc');

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
    $result = "INVALID_COMMENT";
  }
  else {
    $comment->save();
    $result = "OK";
    $message = "Comment posted";
  }

  $response = array("message" => $message, "result" => $result);
  echo json_encode($response);
}

?>
