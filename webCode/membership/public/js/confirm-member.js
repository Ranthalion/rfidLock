$(function(){
	$('.cancel-confirmation').click(function(){
		var btn = $(this);

		swal({   
				title: "Confirm Cancel",
				text: "Are you sure you want to cancel the activation of this member?",
				type: "warning", 
				showCancelButton: true,
				cancelButtonText: "No, let me review",
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, cancel the activation",
				closeOnConfirm: true 
			}, 
			function(){
				window.location = "/";
			});	
		return false;
	});
});