function errorHandler(responseObject) {

	var noUsernameMessage = document.getElementById("no-username");
	var noPasswordMessage = document.getElementById("no-password");
	var badPasswordLengthMessage = document.getElementById("bad-password-length");
	var wrongTakenMessage = document.getElementById("wrong-pass-taken-username");

	var badInput = false;

	if ("noUsername" in responseObject) {
		noUsernameMessage.style.display = "block";
		badInput = true;
	}
	else {
		noUsernameMessage.style.display = "none";
	}

	if ("noPassword" in responseObject) {
		noPasswordMessage.style.display = "block";
		badInput = true;
	}
	else {
		noPasswordMessage.style.display = "none";
	}

	if ("wrongOrTaken" in responseObject) {
		wrongTakenMessage.style.display = "block";
		badInput = true;
	}
	else {
		wrongTakenMessage.style.display = "none";
	}

	if ("badPasswordLength" in responseObject) {
		badPasswordLengthMessage.style.display = "block";
		badInput = true;
	}
	else {
		badPasswordLengthMessage.style.display = "none";
	}

	if (!badInput) {
		window.location = "/~liangt/290/fp/app";
	}
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

function submitButton() {
	document.getElementById("submit").onclick = function() {

		var loginForm = document.getElementById("login-form");
		var loginData = new FormData(loginForm);
		apiCall('POST', 'login.php', loginData, errorHandler)
		//This is to prevent page reload after submission and ruining the AJAX call
		return false;
	}
}

window.onload = function() {
	submitButton();
}