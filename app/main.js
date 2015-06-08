var oldbill;
var newbill;
var savings;

function handleData(responseObject) {

	var address = document.getElementById("address-input").value;
	var usage = document.getElementById("annual-usage-input").value;
	var rate = document.getElementById("electric-rate-input").value;
	var size = document.getElementById("system-size-input").value;

	oldbill = usage * rate / 100;
	savings = responseObject.outputs.ac_annual * rate / 100
	newbill = oldbill - savings;

	document.getElementById("old-bill").innerHTML =
		"Old Annual Bill: $" + oldbill.toFixed(2);
	document.getElementById("new-bill").innerHTML =
		"New Annual Bill: $" + newbill.toFixed(2);
	document.getElementById("savings").innerHTML =
		"Annual Savings: $" + savings.toFixed(2);

	if (savings > oldbill) {
		document.getElementById("oversized").innerHTML =
			"Oversized system; please decrease system size.";
	}
	else {
		document.getElementById("oversized").innerHTML = "";
	}

	$('#acMonthlyLineChart').highcharts({
      title: {
          text: 'Monthly AC Production',
          x: -20 //center
      },
      subtitle: {
          text: 'Source: PVWatts v5',
          x: -20
      },
      xAxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
              'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
      },
      yAxis: {
          title: {
              text: 'Production (kWh)'
          },
          plotLines: [{
              value: 0,
              width: 1,
              color: '#808080'
          }]
      },
      tooltip: {
          valueSuffix: ' kWh'
      },
      legend: {
          layout: 'vertical',
          align: 'right',
          verticalAlign: 'middle',
          borderWidth: 0
      },
      series: [{
          name: address,
          data: responseObject.outputs.ac_monthly
      }]
  })

  document.getElementById("save-sim").style.display = "block";
  document.getElementById("saved").style.display = "none";
}

function apiCall(method, url, POSTData, responseHandler) {
	var httpRequest = new XMLHttpRequest();

		function requestCheck() {
		  if (httpRequest.readyState === 4) {
		    if (httpRequest.status === 200) {
		    	responseHandler(JSON.parse(httpRequest.responseText));
		    }
		    else if (httpRequest.status === 422) {
		    	// PVWatts returns 422 if the address isn't good
		    	document.getElementById("no-address").style.display = "block";
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

		var address = document.getElementById("address-input").value;
		var usage = document.getElementById("annual-usage-input").value;
		var rate = document.getElementById("electric-rate-input").value;
		var size = document.getElementById("system-size-input").value;

		// Validate Inputs

		var noAddress = document.getElementById("no-address");
		var noUsage = document.getElementById("no-annual-usage");
		var noRate = document.getElementById("no-electric-rate");
		var noSize = document.getElementById("no-system-size");

		var badInputs = false;

		if (address == "") {
			noAddress.style.display = "block";
			badInputs = true;
		}
		else
			noAddress.style.display = "none";

		if (usage <= 0) {
			noUsage.style.display = "block";
			badInputs = true;
		}
		else
			noUsage.style.display = "none";

		if (rate <= 0) {
			noRate.style.display = "block";
			badInputs = true;
		}
		else
			noRate.style.display = "none";

		if (size <= 0) {
			noSize.style.display = "block";
			badInputs = true;
		}
		else
			noSize.style.display = "none";

		// If good, do PVWatts API call

		if (!badInputs) {
			var url = 'http://developer.nrel.gov/api/pvwatts/v5.json';
			url = url + "?api_key=nfjHmWbLboUZh2AF390ZvEg8Cxv0i2tOtNOsjVjC"
			url = url + "&address=" + encodeURIComponent(address);
			url = url + "&system_capacity=" + size;
			url = url + "&azimuth=180";
			url = url + "&tilt=25";
			url = url + "&array_type=1";
			url = url + "&module_type=1";
			url = url + "&losses=10";
			apiCall('GET', url, null, handleData)
		}

		return false;
	}
}

function saveSimulation() {
	document.getElementById("save-sim").onclick = function() {
		var simForm = document.getElementById("new-sim");
		var simData = new FormData(simForm);
		simData.append('oldbill', oldbill);
		simData.append('newbill', newbill);
		simData.append('savings', savings);
		apiCall('POST', 'save.php', simData, handleSave);
		return false;
	}
}

function handleSave(responseObject) {
	if (responseObject.success == true) {
		document.getElementById("saved").style.display = "block";
	}
}

function loadSimulation() {
	document.getElementById("load-sim").onclick = function() {
		apiCall('GET','load.php',null,handleLoad);
		return false;
	}
}

function handleLoad(responseObject) {
	var loadedSims = document.getElementById("loaded-sims");
	loadedSims.innerHTML = ""; //Clear first
	if (responseObject.numSims > 0) {
		var table = document.createElement("table");

		var head = document.createElement("thead");
		head.innerHTML =
		"<tr>" +
			"<td>Address</td>" +
			"<td>Usage (kWh)</td>" +
			"<td>Rate (cents/kWh)</td>" +
			"<td>Size (kW)</td>" +
			"<td>Old Bill ($)</td>" +
			"<td>New Bill ($)</td>" +
			"<td>Savings ($)</td>" +
		"</tr>";
		table.appendChild(head);

		var body = document.createElement("tbody");
		for (var i = 0; i < responseObject.numSims; i++) {
			var address = "<td>" + responseObject[i + ""].address + "</td>";
			var usage = "<td>" + responseObject[i + ""].usage + "</td>";
			var rate = "<td>" + responseObject[i + ""].rate + "</td>";
			var size = "<td>" + responseObject[i + ""].sysSize + "</td>";
			var oldbillCell = "<td>" + responseObject[i + ""].oldbill + "</td>";
			var newbillCell = "<td>" + responseObject[i + ""].newbill + "</td>";
			var savingsCell = "<td>" + responseObject[i + ""].savings + "</td>";
			var row = document.createElement("tr");
			row.innerHTML =
				address + usage + rate + size + oldbillCell + newbillCell + savingsCell;
			body.appendChild(row);
		}
		table.appendChild(body);
		loadedSims.appendChild(table);
	}
	else {
		loadedSims.innerHTML = "You have no saved simulations.";
	}
}

window.onload = function() {
	runSimulation();
	saveSimulation();
	loadSimulation();
}