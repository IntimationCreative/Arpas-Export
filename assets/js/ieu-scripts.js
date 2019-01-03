(function() {
	window.addEventListener('DOMContentLoaded', function() {
		// define common variables
		const button = document.getElementById('export');
		const xhttp = new XMLHttpRequest();
		const params = getParams(button);

		function getParams(element) {
			return 'action=ieu_export&security=' + ieu_export.nonce + '&type=' + element.getAttribute('data-type');
		}

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
			//location = response.data.link;
		}

		// Event bindings
		button.addEventListener('click', function(e) {
			e.preventDefault;
			makeRequest(xhttp, params);
		});

		function sendAjaxRequest(data, action, callback = function() {}) {
			data.action = action;
			let urlEncodedData = '';
			let urlEncodedDataPairs = [];
			let name;
			let response = '';

			for (name in data) {
				urlEncodedDataPairs.push(encodeURIComponent(name) + '=' + encodeURIComponent(data[name]));
			}
			urlEncodedData = urlEncodedDataPairs.join('&').replace(/%20/g, '+');

			let xhttp = new XMLHttpRequest();
			xhttp.open('POST', '<?php echo admin_url("admin-ajax.php"); ?>', true);

			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					handleResponse.apply(this, [ this.responseText, callback ]);
				}
			};

			xhttp.setRequestHeader('Access-Control-Allow-Headers', 'x-requested-with');
			xhttp.setRequestHeader('Data-type', 'json');
			xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xhttp.send(urlEncodedData);
		}
	});
})();
