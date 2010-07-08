google.setOnLoadCallback(function(){

	$("#account-accordion").accordion({
		autoHeight: false,
		clearStyle: true,
      animated:true
	});

  $("#ticket-accordion h3 a").css("text-decoration", "none");
});

function toggleCommentBox(id) {
  $("#openticketcommentinput" + id).toggle();
}

function addComment(id, ticketId) {
  var comment = $("#openticketcommentinput" + id + " textarea").val();
  $.post("ajax/comments.php", {action: "create", text: comment, ticketId: ticketId},
    function(data){
      handleAddCommentResponse(data, comment, id);
  }, "json");

}

function resendShippingLabel(key, id) {
  $.post("ajax/tickets.php", {action:"resendLabel", id: id},
    function(data){
      if(data.OK) {
        flashNotice("<div style='padding:15px; text-align:center;'>Your pre-paid FedEx shipping label has been sent to the email you provided!<br /><span style='font-size:smaller;'>It should arrive within a few minutes.</span></div>")
      }
      else {
        flashError(data.message);
      }
    },"json");
}

function cancelTicket(key, id) {
  //TODO: Add a confirm dialog when deleting an account
  //TODO: Make ticket "inactive" instead of deleting from database
  $.post("ajax/tickets.php", {action:"delete", id: id},
    function(data){
      if(data.OK) {
        $("#openticket" + key).fadeOut("slow",function(){
          $("#openticket" + key).remove();
          if($("#openTicketsDiv ul").children().length == 0){
            $("#openTicketsDiv").html("<p>No Open Tickets</p><p><a href='index.php'>Click here to start a new one!</a></p>")
          }
        });
      }
    },"json");
 }


function deleteAccount(key, uId) {
  $.post("ajax/users.php", {action: "delete", id: uId},
    function(data) {
      if(data.OK) {
        $("#userbox"+key).fadeOut("slow");
      }
      else {
        flashError("Error deleting user account!");
      }
    },
    "json"
  );
}

function updateAccount(uId)
{
  $.post("ajax/users.php", {action:"update", id: uId,
      first_name: $("#first_name").val(),
      last_name: $("#last_name").val(),
      email: $("#email").val(),
      street: $("#street").val(),
      city: $("#city").val(),
      state: $("#state").val(),
      zip: $("#zip").val(),
      phone: $("#phone").val()},
    function(data){
      if(data.OK){
        flashNotice(data.message);
      }
      else {
        flashError(data.message);
      }
    },"json");
}

function handleAddCommentResponse(data, comment, id){
  if(data.OK) {
    var prefix;
    if(data.commentType == "0") {
      prefix = "Q. "
    }
    else{
      prefix = "A. "
    }
    $("#openticketcomments" + id).append("<p class='commentType"+data.commentType+"'>"+prefix+comment+"</p>").fadeIn("slow");
    toggleCommentBox(id);
    flashNotice("Your question has been posted successfully! You can expect a response within the hour.")
  }
  else {
    flashError(data.message);
  }
}