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
  $.post("comment.php", {action: "create", text: comment, ticketId: ticketId}, 
    function(data){
      handleAddCommentResponse(data, comment, id);
  }, "json");

}

function cancelTicket(id) {
  $("#openticket" + id).fadeOut("slow",function(){
    $("#openticket" + id).remove();
  });
}

function handleAddCommentResponse(data, comment, id){
  if(data.result == "OK") {
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