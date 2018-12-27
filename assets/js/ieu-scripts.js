(function() {
	window.addEventListener('DOMContentLoaded', function() {
		// define common variables
		const button = document.getElementById('export');
		const xhttp = new XMLHttpRequest();
		const params = 'action=ieu_export&security=' + ieu_export.nonce;

		// XHR request
		function makeRequest(xhttp, params) {
			xhttp.open('POST', 'admin-ajax.php', true);
			xhttp.setRequestHeader('Data-type', 'json');
			xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xhttp.responseType = 'json';
			xhttp.send(params);
			xhttp.onreadystatechange = function() {
				// handle response codes
				if (this.readyState == 4 && this.status == 200) {
					// if we have a successfull request trigger the callback
					// console.log(xhttp);
					let response = this.response;
					handleResponse.apply(this, [ response ]);
				}
			};
		}

		function handleResponse(response) {
			// get the download link
			location = response.data.link;
		}

		// Event bindings
		button.addEventListener('click', function(e) {
			e.preventDefault;
			makeRequest(xhttp, params);
		});
	});
})();
