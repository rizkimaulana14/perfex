<script>
	
	//contract preview file
	function preview_file_staff(invoker){
		'use strict';
		
		var id = $(invoker).attr('id');
		var rel_id = $(invoker).attr('rel_id');
		view_hrmstaff_file(id, rel_id);
	}

	 //function view hrm_file
	 function view_hrmstaff_file(id, rel_id) {   
	 	'use strict';

	 	$('#contract_file_data').empty();
	 	$("#contract_file_data").load(admin_url + 'hr_profile/hrm_file_contract/' + id + '/' + rel_id, function(response, status, xhr) {
	 		if (status == "error") {
	 			alert_float('danger', xhr.statusText);
	 		}
	 	});
	 }

	</script>