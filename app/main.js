function handler(responseObject) {
	document.getElementById("simulation").innerHTML = JSON.stringify(responseObject);
}

function apiCall(method, url, POSTData, responseHandler) {
	var httpRequest = new XMLHttpRequest();

		function requestCheck() {
		  if (httpRequest.readyState === 4) {
		    if (httpRequest.status === 200) {
		    	responseHandler(JSON.parse(httpRequest.responseText));
		    }
		    else {
		      alert('Request Status: ' + httpRequest.status);
		    }
		  }
		}

		httpRequest.onreadystatechange = requestCheck;

		httpRequest.open(method, url, true);
		httpRequest.send(POSTData);
}

function runSimulation() {
	document.getElementById("run-simulation").onclick = function() {
		var url = 'http://developer.nrel.gov/api/pvwatts/v5.json?api_key=nfjHmWbLboUZh2AF390ZvEg8Cxv0i2tOtNOsjVjC';
		url = url + "&address=" + encodeURIComponent(document.getElementById("address-input").value);
		url = url + "&system_capacity=" + document.getElementById("system-size-input").value;
		url = url + "&azimuth=180";
		url = url + "&tilt=25";
		url = url + "&array_type=1";
		url = url + "&module_type=1";
		url = url + "&losses=10";
		apiCall('GET', url, null, handler)
		return false;
	}
}

window.onload = function() {
	runSimulation();
}