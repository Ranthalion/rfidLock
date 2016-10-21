$(function(){
	$('.disable-resource').click(function(){
		var resource = $(this).closest('tr').find('td:first').text();
		var btn = $(this);

		swal({   
				title: "Confirm Disable",
				text: "Are you sure you want to disable " + resource + "?",
				type: "warning", 
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Yes, disable",
				closeOnConfirm: true 
			}, 
			function(){
				btn.closest('form').submit();
			});	
		return false;
	});
});