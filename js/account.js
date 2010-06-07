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
    $("#openticketcomments" + id).append(comment).fadeIn("slow");
    toggleCommentBox(id);
  }
  else {
    flashError(data.message);
  }
}