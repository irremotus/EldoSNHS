function request(page, requestData, successFunc, failFunc, async, debug) {
	async = (async != null) ? async : true;
	debug = (debug != null) ? debug : false;
	
	return $.ajax({
		type: 'POST',
		url: page,
		data: requestData,
		success: function(data, status, xhr) {
			result = JSON.parse(data);
			
			if(result.status == true) {
				if(successFunc) successFunc(result);
			} else {
				if(failFunc) failFunc(result);
			}
		},
		async: async
	});
}