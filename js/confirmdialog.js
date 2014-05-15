function confirmDialog(form){
			bootbox.dialog({
				message: "Are you sure?",
				title: "Confirm",
				buttons: {
					yes: {
						label: "OK",
						className: "btn-success",
						callback: function() {
							return true;
						}
					},
					cancel: {
						label: "Cancel",
						className: "btn-danger",
						callback: function() {
							return false;
						}
					},
				}
			});
	}