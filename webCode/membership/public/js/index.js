$(function(){
	
	$('.revoke-member').click(function(){
		var member = $(this).closest('tr').find('td:first').text();
		var btn = $(this);

		swal({   
				title: "Confirm Revoke",
				text: "Are you sure you want to revoke membership for " + member + "?",
				type: "warning", 
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, revoke",
				closeOnConfirm: true 
			}, 
			function(){
				btn.closest('form').submit();
			});	
		return false;
	});
});