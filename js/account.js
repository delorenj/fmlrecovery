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

function addComment(id) {
  toggleCommentBox(id);

}

function cancelTicket(id) {
  $("#openticket" + id).fadeOut("slow",function(){
    $("#openticket" + id).remove();
  });
}